document.addEventListener("DOMContentLoaded", function () {

    // =========================
    // API BASE
    // =========================
    const API = "controller/";

    // =========================
    // TOAST NOTIFICATION
    // =========================
    function toast(msg) {
        const div = document.createElement("div");
        div.className = "toast-msg";
        div.innerText = msg;

        document.body.appendChild(div);

        setTimeout(() => div.remove(), 2500);
    }

    // =========================
    // BADGE UPDATER (UNIFIED)
    // =========================
    function updateBadge(selector, count) {
        const el = document.querySelector(selector);
        if (!el) return;

        el.innerText = count;

        if (count <= 0) {
            el.style.display = "none";
        } else {
            el.style.display = "";
        }
    }

    // =========================
    // FETCH WRAPPER
    // =========================
    async function post(url, body) {
        const res = await fetch(API + url, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body
        });

        return await res.json();
    }

    // =========================
    // ADD TO CART
    // =========================
    window.addToCart = async function (productId, qty = 1) {
        try {
            const data = await post(
                "cartprocess.php",
                `action=add&product_id=${productId}&quantity=${qty}`
            );

            toast(data.message);

            if (data.cartCount !== undefined) {
                updateBadge(".cart-count", data.cartCount);
            }

        } catch (err) {
            console.error(err);
            toast("Failed to add to cart");
        }
    };

    // =========================
    // REMOVE FROM CART
    // =========================
    window.removeFromCart = async function (cartId) {
        try {
            const data = await post(
                "cartprocess.php",
                `action=remove&cart_id=${cartId}`
            );

            toast(data.message);

            updateBadge(".cart-count", data.cartCount || 0);

            location.reload();

        } catch (err) {
            console.error(err);
            toast("Failed to remove item");
        }
    };

    // =========================
    // UPDATE CART
    // =========================
    window.updateCart = async function (cartId, qty) {
        try {
            const data = await post(
                "cartprocess.php",
                `action=update&cart_id=${cartId}&quantity=${qty}`
            );

            toast(data.message);

            updateBadge(".cart-count", data.cartCount || 0);

        } catch (err) {
            console.error(err);
            toast("Update failed");
        }
    };

    // =========================
    // CLEAR CART
    // =========================
    window.clearCart = async function () {
        try {
            const data = await post(
                "cartprocess.php",
                "action=clear"
            );

            toast(data.message);

            updateBadge(".cart-count", 0);

            location.reload();

        } catch (err) {
            console.error(err);
            toast("Failed to clear cart");
        }
    };

    // =========================
    // TOGGLE WISHLIST
    // =========================
    window.toggleWishlist = async function (productId) {
        try {
            const data = await post(
                "addtowishlist.php",
                `product_id=${productId}`
            );

            toast(data.message);

            if (data.wishlistCount !== undefined) {
                updateBadge(".wishlist-count", data.wishlistCount);
            }

        } catch (err) {
            console.error(err);
            toast("Wishlist error");
        }
    };

});