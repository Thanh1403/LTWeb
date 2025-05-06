<?php
include '../includes/header.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $pid = intval($_POST['product_id']);
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]++;
    } else {
        $_SESSION['cart'][$pid] = 1;
    }

    // Optional: redirect để tránh submit lại
    header("Location: product-detail.php?id=$pid");
    exit();
}


// Lấy id từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;

if ($id > 0) {
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<p class='text-danger'>Lỗi truy vấn: " . $e->getMessage() . "</p>";
    }
}
?>

<div class="container py-5">
    <?php if ($product): ?>
        <div class="row">
            <div class="col-md-6">
                <img src="../<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6">
                <h2 class="mb-3"><?= htmlspecialchars($product['name']) ?></h2>

                <?php if ($product['rating']): ?>
                    <div class="mb-3 text-warning">
                        <?php for ($i = 0; $i < $product['rating']; $i++): ?>
                            <i class="bi bi-star-fill"></i>
                        <?php endfor; ?>
                        <span class="text-muted ms-2">(<?= $product['rating'] ?>/5)</span>
                    </div>
                <?php endif; ?>

                <h4 class="text-success mb-3">$<?= number_format($product['price'], 2) ?></h4>

                <?php if ($product['is_sale']): ?>
                    <span class="badge bg-danger mb-3">Sale</span>
                <?php endif; ?>

                <?php if (!empty($product['description'])): ?>
                    <p class="lead mt-3 mb-4">
                        <?= nl2br(htmlspecialchars($product['description'])) ?>
                    </p>
                <?php else: ?>
                    <p class="lead mt-3 mb-4 text-muted">
                        Mô tả sản phẩm đang được cập nhật.
                    </p>
                <?php endif; ?>

                <p><strong>Tình trạng:</strong> Còn hàng</p>

                <div class="d-grid gap-2 d-md-block mt-4">
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                        </button>
                    </form>
                    <a href="#" class="btn btn-outline-primary">Mua ngay</a>
                </div>

                <div class="mt-4">
                    <a href="products.php" class="btn btn-sm btn-secondary">← Quay lại danh sách</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p class="text-danger">Không tìm thấy sản phẩm.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
