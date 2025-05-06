<?php include '../includes/header.php'; ?>
<?php include '../includes/db.php'; ?>

<?php

// Xử lý phân trang
$limit = 16;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Xử lý tìm kiếm
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$params = [];
$sql = "SELECT * FROM products";

if (!empty($keyword)) {
    $sql .= " WHERE name LIKE :keyword";
    $params[':keyword'] = "%" . $keyword . "%";
}

// Lấy tổng số sản phẩm
$countSql = str_replace("SELECT *", "SELECT COUNT(*) as total", $sql);
$countStmt = $conn->prepare($countSql);
$countStmt->execute($params);
$total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($total / $limit);

// Lấy sản phẩm theo trang
$sql .= " LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);

// Bind params
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Header -->
<header class="bg-dark py-5">
    <div class="container px-4 px-lg-5 my-4">
        <div class="text-white">
            <h1 class="display-5 fw-bolder">Danh sách sản phẩm</h1>
            <p class="lead fw-normal text-white-50 mb-0">Tìm kiếm và duyệt qua các sản phẩm của FutureGear</p>
        </div>
    </div>
</header>

<?php

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $id = intval($_POST['product_id']);
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
}
?>


<!-- Tìm kiếm -->
<div class="container mt-4 mb-2">
    <form method="GET" action="products.php" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="keyword" class="form-control" placeholder="Tìm sản phẩm..." value="<?= htmlspecialchars($keyword) ?>">
        </div>
        <div class="col-auto">
            <button class="btn btn-success" type="submit">
                <i class="bi bi-search"></i> Tìm kiếm
            </button>
        </div>
    </form>
</div>

<!-- Danh sách sản phẩm -->
<section class="py-3">
    <div class="container px-4 px-lg-5 mt-3">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <?php foreach ($products as $p): ?>
                <div class="col mb-5">
                    <div class="card h-100">
                        <?php if ($p['is_sale']): ?>
                            <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
                        <?php endif; ?>

                        <img class="card-img-top" src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" />

                        <div class="card-body p-4">
                            <div class="text-center">
                                <h5 class="fw-bolder"><?= htmlspecialchars($p['name']) ?></h5>
                                
                                <?php if ($p['rating']): ?>
                                    <div class="d-flex justify-content-center small text-warning mb-2">
                                        <?php for ($i = 0; $i < $p['rating']; $i++): ?>
                                            <div class="bi-star-fill"></div>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>

                                $<?= number_format($p['price'], 2) ?>
                            </div>
                        </div>

                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                        <div class="text-center">
                            <div class="d-grid gap-2">
                                <a class="btn btn-primary btn-sm rounded shadow-sm" href="product-detail.php?id=<?= $p['id'] ?>">
                                    <i class="bi bi-info-circle me-1"></i> Xem chi tiết
                                </a>
                                <form method="POST">
                                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm rounded shadow-sm w-100">
                                        <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                                    </button>
                                </form>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Phân trang -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                            <a class="page-link" href="products.php?page=<?= $i ?>&keyword=<?= urlencode($keyword) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
