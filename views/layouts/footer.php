<!-- views/home/footer.php -->
  <head>
    <meta charset="UTF-8" />
  <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/footer.css">
       <!-- <link rel="stylesheet" href="public/css/globals.css"> -->
  </head>
   <body>
    <div class="box">
        <footer class="FOOTER" role="contentinfo">
            <div class="footer-container">
                <!-- Logo và Tên Store -->
                <div class="footer-logo">
                    <img class="rectangle" src="public/images/1.png" alt="CHÚC Store logo" />
                    <h1 class="text-wrapper-13">Dũng Store</h1>
                </div>

                <!-- Danh mục -->
                <nav class="footer-section" aria-labelledby="category-heading">
                    <h3 id="category-heading" class="footer-heading">Danh mục</h3>
                    <ul class="footer-list" role="list">
                        <li><a href="#" class="footer-link">Air Max</a></li>
                        <li><a href="#" class="footer-link">Jordan</a></li>
                        <li><a href="#" class="footer-link">Free</a></li>
                        <li><a href="#" class="footer-link">React</a></li>
                    </ul>
                </nav>

                <!-- Links -->
                <nav class="footer-section" aria-labelledby="links-heading">
                    <h3 id="links-heading" class="footer-heading">Links</h3>
                    <ul class="footer-list" role="list">
                        <li><a href="#" class="footer-link">Trang chủ</a></li>
                        <li><a href="#" class="footer-link">Cửa hàng</a></li>
                        <li><a href="#" class="footer-link">Về chúng tôi</a></li>
                        <li><a href="#" class="footer-link">Liên hệ</a></li>
                    </ul>
                </nav>

                <!-- Chăm sóc khách hàng -->
                <section class="footer-section">
                    <h3 class="footer-heading">Chăm sóc khách hàng</h3>
                    <ul class="footer-list" role="list">
                        <li><a href="#" class="footer-link">Chính sách vận chuyển</a></li>
                        <li><a href="#" class="footer-link">Chính sách đổi trả hàng</a></li>
                        <li><a href="#" class="footer-link">Chính sách tích điểm</a></li>
                        <li><a href="?controller=order&action=track_order" class="footer-link">📋 Tra cứu đơn hàng</a></li>
                    </ul>
                </section>

                <!-- Hệ thống cửa hàng -->
                <section class="footer-section">
                    <h3 class="footer-heading">Hệ thống cửa hàng</h3>
                    <address class="footer-address">
                        <p>
                            CS1: 48 Hoàng Sâm, Cầu Giấy, Hà Nội <br><br>
                            <a href="tel:0988875522" class="footer-link">098.887.5522</a>
                        </p>
                        <p>
                            CS2: Tầng 7, Gems Empire Tower 201 <br />
                            Trường Chinh, Thanh Xuân, Hà Nội - <br><br>
                            <a href="tel:0839335522" class="footer-link">0839.33.55.22</a>
                        </p>
                    </address>
                </section>

                <!-- Subscribe -->
                <section class="footer-subscribe">
                    <h3 class="footer-heading">Dũng Store lắng nghe bạn!</h3>
                    <p>
                        Chúng tôi luôn trân trọng và mong đợi nhận được mọi ý kiến đóng góp từ khách hàng để có thể nâng cấp trải nghiệm dịch vụ và sản phẩm tốt hơn nữa.
                    </p>
                    <form role="form" aria-label="Đăng ký nhận tin">
                        <div class="subscribe-form">
                            <input type="email" id="email-input" name="email" class="email-input" placeholder="Enter Your Email Address" required />
                            <button type="submit" class="subscribe-button">SUBSCRIBE</button>
                        </div>
                        <div id="email-description" class="sr-only">Nhập địa chỉ email để đăng ký nhận tin tức và ưu đãi</div>
                    </form>
                </section>
            </div>

            <!-- Phần dưới cùng -->
            <div class="footer-bottom">
                <hr class="footer-divider" />
                <p class="footer-info">
                    ĐKKD:03034450 - Cấp ngày: 04/05/2023 - Nơi cấp: Hà Nội<br />
                    Hộ Kinh Doanh Đại Lý Sneaker MST: 882856371-001
                </p>
            </div>
        </footer>
    </div>
</body> 
</html>

<script src="public/js/script.js"></script>