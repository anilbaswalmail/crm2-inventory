CREATE TABLE IF NOT EXISTS sites (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    site_url VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    da TINYINT UNSIGNED NOT NULL DEFAULT 0,
    dr TINYINT UNSIGNED NOT NULL DEFAULT 0,
    traffic INT UNSIGNED NOT NULL DEFAULT 0,
    price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_site_url (site_url),
    INDEX idx_category (category),
    INDEX idx_country (country),
    INDEX idx_da (da),
    INDEX idx_dr (dr),
    INDEX idx_price (price),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
