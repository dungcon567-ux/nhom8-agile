# Hướng dẫn sửa lỗi thanh toán COD

## 🔍 **Các lỗi có thể gặp:**

### 1. **Lỗi Database**
- Cột `order_code` chưa được tạo trong bảng `orders`
- Cấu trúc bảng không đúng
- Kết nối database bị lỗi

### 2. **Lỗi Validation**
- JavaScript validation không hoạt động
- Form không submit được
- Dữ liệu không được gửi đúng

### 3. **Lỗi Session**
- Session không được khởi tạo
- Dữ liệu session bị mất

## 🛠️ **Cách sửa lỗi:**

### **Bước 1: Kiểm tra Database**
```sql
-- Chạy file update_database.sql để cập nhật cấu trúc bảng
-- Hoặc chạy các lệnh sau:

USE chuc_store;

-- Kiểm tra cấu trúc bảng orders
DESCRIBE orders;

-- Thêm cột order_code nếu chưa có
ALTER TABLE orders ADD COLUMN IF NOT EXISTS order_code VARCHAR(20) UNIQUE;

-- Cập nhật trạng thái
ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned') DEFAULT 'pending';
```

### **Bước 2: Test Database**
Truy cập: `http://localhost/chuc_store/test_checkout.php`
- Kiểm tra kết nối database
- Test tạo đơn hàng
- Xem lỗi chi tiết

### **Bước 3: Test Checkout đơn giản**
Truy cập: `http://localhost/chuc_store/?controller=test&action=checkout`
- Sử dụng form đơn giản không có JavaScript
- Dễ debug hơn

### **Bước 4: Debug chi tiết**
Truy cập: `http://localhost/chuc_store/debug_checkout.php`
- Xem session data
- Xem POST data
- Kiểm tra cấu trúc database

## 📋 **Các file đã được tạo/sửa:**

### ✅ **Files mới:**
1. `debug_checkout.php` - Debug database và models
2. `test_checkout.php` - Test tạo đơn hàng
3. `views/orders/checkout_simple.php` - Form checkout đơn giản
4. `controllers/TestController.php` - Controller test
5. `HUONG_DAN_SUA_LOI_CHECKOUT.md` - Hướng dẫn này

### ✅ **Files đã sửa:**
1. `models/Order.php` - Cải thiện error handling
2. `controllers/OrderController.php` - Cải thiện error handling
3. `views/orders/checkout.php` - Sửa lỗi text "b bnbn"

## 🚀 **Cách test:**

### **Test 1: Database**
```
http://localhost/chuc_store/test_checkout.php
```

### **Test 2: Checkout đơn giản**
```
http://localhost/chuc_store/?controller=test&action=checkout
```

### **Test 3: Debug**
```
http://localhost/chuc_store/debug_checkout.php
```

## 🔧 **Nếu vẫn lỗi:**

### **Kiểm tra Error Log:**
```bash
# Xem PHP error log
tail -f /var/log/apache2/error.log
# hoặc
tail -f /var/log/nginx/error.log
```

### **Kiểm tra Database Log:**
```sql
-- Xem log MySQL
SHOW VARIABLES LIKE 'log_error';
```

### **Test từng bước:**
1. Đăng nhập user
2. Thêm sản phẩm vào giỏ hàng
3. Vào checkout
4. Điền form và submit
5. Kiểm tra database

## 📞 **Liên hệ hỗ trợ:**
Nếu vẫn gặp lỗi, hãy:
1. Chụp màn hình lỗi
2. Copy error log
3. Cho biết bước nào bị lỗi

## ✅ **Kết quả mong đợi:**
- Đơn hàng được tạo thành công
- Mã đơn hàng được hiển thị
- Giỏ hàng được xóa
- Trạng thái "Đang chờ"

