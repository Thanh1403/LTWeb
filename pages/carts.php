<?php
session_start();
include '../includes/header.php';
include '../includes/db.php';

// Xử lý cập nhật số lượng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['increase'])) {
        $_SESSION['cart'][$_POST['product_id']]++;
    } elseif (isset($_POST['decrease'])) {
        if ($_SESSION['cart'][$_POST['product_id']] > 1) {
            $_SESSION['cart'][$_POST['product_id']]--;
        } else {
            unset($_SESSION['cart'][$_POST['product_id']]);
        }
    } elseif (isset($_POST['remove'])) {
        unset($_SESSION['cart'][$_POST['product_id']]);
    }
}

$total = 0;

if (!empty($_SESSION['cart'])) {
    include '../includes/db.php';

    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container py-5">
    <h2 class="mb-4">Giỏ hàng</h2>

    <?php if (!empty($products)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): 
                    $qty = $_SESSION['cart'][$p['id']];
                    $subtotal = $p['price'] * $qty;
                    $total += $subtotal;
                ?>
                    <tr>
                        <td width="80"><img src="../<?= htmlspecialchars($p['image']) ?>" width="70"></td>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td>$<?= number_format($p['price'], 2) ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                <button class="btn btn-sm btn-outline-secondary" name="decrease">-</button>
                            </form>
                            <span class="mx-2"><?= $qty ?></span>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                <button class="btn btn-sm btn-outline-secondary" name="increase">+</button>
                            </form>
                        </td>
                        <td>$<?= number_format($subtotal, 2) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                <button class="btn btn-sm btn-danger" name="remove">Xóa</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                    <td colspan="2"><strong>$<?= number_format($total, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p>Giỏ hàng của bạn đang trống.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
