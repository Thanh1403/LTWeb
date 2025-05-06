<?php include 'includes/header.php'; ?>  
<?php include 'includes/db.php'; ?>

<!-- Header -->
<header class="bg-dark py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
            <h1 class="display-4 fw-bolder">Shop in style</h1>
            <p class="lead fw-normal text-white-50 mb-0">With this shop homepage template</p>
        </div>
    </div>
</header>

<!-- Section -->
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

        <?php
        try {
            $stmt = $conn->query("SELECT * FROM products LIMIT 12");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($products as $p): ?>
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
                                <div class="d-grid mb-2">
                                    <a class="btn btn-outline-dark mt-auto" href="pages/product-detail.php?id=<?= $p['id'] ?>">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach;

        } catch (PDOException $e) {
            echo "<p class='text-danger'>Lỗi truy vấn: " . $e->getMessage() . "</p>";
        }
        ?>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>