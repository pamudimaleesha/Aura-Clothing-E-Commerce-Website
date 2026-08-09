<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');


// =====================================
// CHECK USER LOGIN
// =====================================
if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Please login to continue shopping'
    ]);

    exit;
}


// =====================================
// GET USER ID
// =====================================
$user_id = $_SESSION['user_id'];


// =====================================
// CHECK STOCK AVAILABILITY
// =====================================
function checkStockAvailability($conn, $product_id, $requested_quantity)
{
    $stmt = $conn->prepare("
        SELECT qty 
        FROM products 
        WHERE id = ?
    ");

    $stmt->execute([$product_id]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {

        return [
            'status' => false,
            'message' => 'Product not found'
        ];
    }

    if ($product['qty'] < $requested_quantity) {

        return [
            'status' => false,
            'message' => 'Only ' . $product['qty'] . ' item(s) available in stock',
            'available' => $product['qty']
        ];
    }

    return ['status' => true];
}


// =====================================
// GET CART TOTAL
// =====================================
function getCartTotal($conn, $user_id)
{
    $stmt = $conn->prepare("
        SELECT SUM(c.quantity * p.price) AS total
        FROM cart c
        INNER JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");

    $stmt->execute([$user_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total'] ?: 0;
}


// =====================================
// GET CART COUNT
// =====================================
function getCartCount($conn, $user_id)
{
    $stmt = $conn->prepare("
        SELECT COUNT(*) 
        FROM cart 
        WHERE user_id = ?
    ");

    $stmt->execute([$user_id]);

    return (int) $stmt->fetchColumn();
}


// =====================================
// ADD TO CART
// =====================================
if (isset($_POST['action']) && $_POST['action'] === 'add') {

    $product_id = isset($_POST['product_id'])
        ? (int) $_POST['product_id']
        : 0;

    $quantity = isset($_POST['quantity'])
        ? (int) $_POST['quantity']
        : 1;


    if ($product_id <= 0) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid product selected',
            'cartCount' => getCartCount($conn, $user_id)
        ]);

        exit;
    }


    // =====================================
    // CHECK STOCK
    // =====================================
    $stock_check = checkStockAvailability(
        $conn,
        $product_id,
        $quantity
    );

    if (!$stock_check['status']) {

        echo json_encode([
            'status' => 'error',
            'message' => $stock_check['message'],
            'cartCount' => getCartCount($conn, $user_id)
        ]);

        exit;
    }


    // =====================================
    // CHECK EXISTING CART ITEM
    // =====================================
    $stmt = $conn->prepare("
        SELECT cart_id, quantity
        FROM cart
        WHERE user_id = ?
        AND product_id = ?
    ");

    $stmt->execute([$user_id, $product_id]);

    $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);


    // =====================================
    // PRODUCT ALREADY EXISTS
    // =====================================
    if ($existing_item) {

        $new_quantity = $existing_item['quantity'] + $quantity;

        $stock_check = checkStockAvailability(
            $conn,
            $product_id,
            $new_quantity
        );

        if (!$stock_check['status']) {

            echo json_encode([
                'status' => 'error',
                'message' => $stock_check['message'],
                'cartCount' => getCartCount($conn, $user_id)
            ]);

            exit;
        }


        // UPDATE QUANTITY
        $update_stmt = $conn->prepare("
            UPDATE cart
            SET quantity = ?
            WHERE cart_id = ?
        ");

        if ($update_stmt->execute([
            $new_quantity,
            $existing_item['cart_id']
        ])) {

            echo json_encode([
                'status' => 'success',
                'message' => 'Cart updated successfully',
                'cart_total' => getCartTotal($conn, $user_id),
                'cartCount' => getCartCount($conn, $user_id)
            ]);

        } else {

            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update cart',
                'cartCount' => getCartCount($conn, $user_id)
            ]);
        }

    } else {

        // =====================================
        // INSERT NEW PRODUCT
        // =====================================
        $insert_stmt = $conn->prepare("
            INSERT INTO cart (
                user_id,
                product_id,
                quantity,
                added_at
            )
            VALUES (?, ?, ?, NOW())
        ");

        if ($insert_stmt->execute([
            $user_id,
            $product_id,
            $quantity
        ])) {

            echo json_encode([
                'status' => 'success',
                'message' => 'Product added to cart',
                'cart_total' => getCartTotal($conn, $user_id),
                'cartCount' => getCartCount($conn, $user_id)
            ]);

        } else {

            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to add product to cart',
                'cartCount' => getCartCount($conn, $user_id)
            ]);
        }
    }
}


// =====================================
// REMOVE FROM CART
// =====================================
if (isset($_POST['action']) && $_POST['action'] === 'remove') {

    $cart_id = isset($_POST['cart_id'])
        ? (int) $_POST['cart_id']
        : 0;


    if ($cart_id <= 0) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid cart item',
            'cartCount' => getCartCount($conn, $user_id)
        ]);

        exit;
    }


    $stmt = $conn->prepare("
        DELETE FROM cart
        WHERE cart_id = ?
        AND user_id = ?
    ");

    if ($stmt->execute([$cart_id, $user_id])) {

        echo json_encode([
            'status' => 'success',
            'message' => 'Item removed from cart',
            'cart_total' => getCartTotal($conn, $user_id),
            'cartCount' => getCartCount($conn, $user_id)
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to remove item',
            'cartCount' => getCartCount($conn, $user_id)
        ]);
    }
}


// =====================================
// UPDATE CART QUANTITY
// =====================================
if (isset($_POST['action']) && $_POST['action'] === 'update') {

    $cart_id = isset($_POST['cart_id'])
        ? (int) $_POST['cart_id']
        : 0;

    $quantity = isset($_POST['quantity'])
        ? (int) $_POST['quantity']
        : 1;


    if ($cart_id <= 0 || $quantity <= 0) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid request',
            'cartCount' => getCartCount($conn, $user_id)
        ]);

        exit;
    }


    // GET PRODUCT
    $stmt = $conn->prepare("
        SELECT product_id
        FROM cart
        WHERE cart_id = ?
        AND user_id = ?
    ");

    $stmt->execute([$cart_id, $user_id]);

    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$cart_item) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Cart item not found',
            'cartCount' => getCartCount($conn, $user_id)
        ]);

        exit;
    }


    // STOCK CHECK
    $stock_check = checkStockAvailability(
        $conn,
        $cart_item['product_id'],
        $quantity
    );

    if (!$stock_check['status']) {

        echo json_encode([
            'status' => 'error',
            'message' => $stock_check['message'],
            'cartCount' => getCartCount($conn, $user_id)
        ]);

        exit;
    }


    // UPDATE CART
    $update_stmt = $conn->prepare("
        UPDATE cart
        SET quantity = ?
        WHERE cart_id = ?
        AND user_id = ?
    ");

    if ($update_stmt->execute([
        $quantity,
        $cart_id,
        $user_id
    ])) {

        echo json_encode([
            'status' => 'success',
            'message' => 'Cart updated successfully',
            'cart_total' => getCartTotal($conn, $user_id),
            'cartCount' => getCartCount($conn, $user_id)
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update cart',
            'cartCount' => getCartCount($conn, $user_id)
        ]);
    }
}


// =====================================
// CLEAR ENTIRE CART
// =====================================
if (isset($_POST['action']) && $_POST['action'] === 'clear') {

    $stmt = $conn->prepare("
        DELETE FROM cart
        WHERE user_id = ?
    ");

    if ($stmt->execute([$user_id])) {

        echo json_encode([
            'status' => 'success',
            'message' => 'Your cart has been cleared',
            'cart_total' => 0,
            'cartCount' => 0
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to clear cart',
            'cartCount' => getCartCount($conn, $user_id)
        ]);
    }
}
?>