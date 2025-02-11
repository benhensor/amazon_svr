INSERT INTO users (user_id, first_name, last_name, email, password, created_at, updated_at) VALUES
(2, 'Testy', 'McTestFace', 'test@testing.com', '$2a$10$jhbClG8MNtoPL5WRNCWJC.8/bP2GQWn2pg1KFz.vhau8o08z/VvjC', '2024-11-12 16:26:22', '2024-11-12 16:26:22'),
(3, 'Testy', 'Twoshoes', 'testing2@test.com', '$2a$10$.mhiDaOKRsVY4ZDGm1jL6eioShq3ox3s5KduA97zyWpidt6Xh8gLa', '2024-11-12 23:42:05', '2024-11-12 23:42:05'),
(4, 'Lemon', 'Zesty', 'testing3@test.com', '$2a$10$GcUGVivimiTOoKa.n6GVSe3h/Ka9E7OvJ60fdl3GlNfVmEDOhb/Em', '2024-11-13 17:05:56', '2024-11-13 17:05:56');


INSERT INTO addresses (user_id, full_name, phone_number, address_line1, address_line2, city, county, postcode, country, is_default, is_billing, delivery_instructions, address_type, created_at, updated_at) VALUES
(2, 'Testy McTestFace', '0123456789', '1 Testing Street', NULL, 'Testington', 'Testfordshire', 'TE1 2ST', 'United Kingdom', TRUE, TRUE, 'Leave in the shed', 'home', '2024-11-12 16:26:38', '2024-11-12 16:26:38'),
(3, 'Testy Twoshoes', '07771771771', '10 Queensway', NULL, 'Horsham', 'West Sussex', 'RH13 5AP', 'United Kingdom', TRUE, TRUE, 'Leave in the porch', 'home', '2024-11-13 14:52:47', '2024-11-13 14:52:47'),
(4, 'Lemon Zesty', '0123456789', '23 Endless Street', 'Sadly Broke', 'Bristol', 'Avon', 'BS13 7HW', 'United Kingdom', TRUE, TRUE, 'Leave with neighbour', 'home', '2024-11-13 17:22:06', '2024-11-13 17:22:06');


INSERT INTO baskets (user_id, items, items_count, total, status, last_modified, created_at, updated_at) VALUES
(2, JSON_ARRAY(), 3, 89.97, 'active', '2024-12-04 18:39:43', '2024-11-23 12:36:48', '2024-12-04 18:39:43');


INSERT INTO basket_items (basket_item_id, basket_id, product_data, quantity, is_selected, created_at, updated_at) VALUES
('6d13849f-2700-4faa-8ee1-31c726de222f', 2, 
'{
  "id": 84,
  "sku": "QA703Y60",
  "meta": {
    "qrCode": "https://assets.dummyjson.com/public/qr-code.png",
    "barcode": "3072645939073",
    "created_at": "2024-05-23 08:56:21",
    "updated_at": "2024-05-23 08:56:21"
  },
  "tags": ["clothing", "men\'s t-shirts"],
  "brand": "Gigabyte",
  "price": 24.99,
  "stock": 64,
  "title": "Gigabyte Aorus Men Tshirt",
  "images": [
    "https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/1.png",
    "https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/2.png",
    "https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/3.png",
    "https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/4.png"
  ],
  "rating": 4.95,
  "weight": 2,
  "reviews": [
    {
      "date": "2024-05-23 08:56:21",
      "rating": 4,
      "comment": "Highly recommended!",
      "reviewerName": "Logan Lawson",
      "reviewerEmail": "logan.lawson@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 4,
      "comment": "Great value for money!",
      "reviewerName": "Logan Lawson",
      "reviewerEmail": "logan.lawson@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 5,
      "comment": "Great value for money!",
      "reviewerName": "Oscar Powers",
      "reviewerEmail": "oscar.powers@x.dummyjson.com"
    }
  ],
  "category": "mens-shirts",
  "thumbnail": "https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/thumbnail.png",
  "dimensions": {
    "depth": 5.66,
    "width": 8.54,
    "height": 23.52
  },
  "description": "The Gigabyte Aorus Men Tshirt is a cool and casual shirt for gaming enthusiasts. With the Aorus logo and sleek design, it\'s perfect for expressing your gaming style.",
  "returnPolicy": "30 days return policy",
  "availabilityStatus": "In Stock",
  "discountPercentage": 12.6,
  "shippingInformation": "Ships in 1 week",
  "warrantyInformation": "1 month warranty",
  "minimumOrderQuantity": 4
}', 1, TRUE, '2024-12-04 18:39:43', '2024-12-04 18:39:43'),

('71b98a08-240d-4404-8c6b-e69a4b49750e', 2, 
'{
  "id": 85,
  "sku": "TWL7HRBB",
  "meta": {
    "qrCode": "https://assets.dummyjson.com/public/qr-code.png",
    "barcode": "9383795178668",
    "created_at": "2024-05-23 08:56:21",
    "updated_at": "2024-05-23 08:56:21"
  },
  "tags": ["clothing", "men\'s shirts"],
  "brand": "Classic Wear",
  "price": 34.99,
  "stock": 65,
  "title": "Man Plaid Shirt",
  "images": [
    "https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/1.png",
    "https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/2.png",
    "https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/3.png",
    "https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/4.png"
  ],
  "rating": 3.66,
  "weight": 1,
  "reviews": [
    {
      "date": "2024-05-23 08:56:21",
      "rating": 3,
      "comment": "Would not buy again!",
      "reviewerName": "Nora Mills",
      "reviewerEmail": "nora.mills@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 1,
      "comment": "Very disappointed!",
      "reviewerName": "Addison Ward",
      "reviewerEmail": "addison.ward@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 4,
      "comment": "Awesome product!",
      "reviewerName": "Emily Brown",
      "reviewerEmail": "emily.brown@x.dummyjson.com"
    }
  ],
  "category": "mens-shirts",
  "thumbnail": "https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/thumbnail.png",
  "dimensions": {
    "depth": 7.77,
    "width": 29.56,
    "height": 29.84
  },
  "description": "The Man Plaid Shirt is a timeless and versatile men\'s shirt with a classic plaid pattern. Its comfortable fit and casual style make it a wardrobe essential for various occasions.",
  "returnPolicy": "30 days return policy",
  "availabilityStatus": "In Stock",
  "discountPercentage": 17.53,
  "shippingInformation": "Ships in 1 week",
  "warrantyInformation": "Lifetime warranty",
  "minimumOrderQuantity": 1
}', 1, TRUE, '2024-12-04 18:39:43', '2024-12-04 18:39:43'),

('9cd6cd15-5ceb-404b-a433-fa51188386d8', 2, 
'{
  "id": 83,
  "sku": "6RJDTVCU",
  "meta": {
    "qrCode": "https://assets.dummyjson.com/public/qr-code.png",
    "barcode": "8840720880947",
    "created_at": "2024-05-23 08:56:21",
    "updated_at": "2024-05-23 08:56:21"
  },
  "tags": ["clothing", "men\'s shirts"],
  "brand": "Fashion Trends",
  "price": 29.99,
  "stock": 44,
  "title": "Blue & Black Check Shirt",
  "images": [
    "https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/1.png",
    "https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/2.png",
    "https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/3.png",
    "https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/4.png"
  ],
  "rating": 4.19,
  "weight": 6,
  "reviews": [
    {
      "date": "2024-05-23 08:56:21",
      "rating": 4,
      "comment": "Highly recommended!",
      "reviewerName": "Mason Parker",
      "reviewerEmail": "mason.parker@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 5,
      "comment": "Highly impressed!",
      "reviewerName": "Avery Perez",
      "reviewerEmail": "avery.perez@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 5,
      "comment": "Fast shipping!",
      "reviewerName": "Nathan Reed",
      "reviewerEmail": "nathan.reed@x.dummyjson.com"
    }
  ],
  "category": "mens-shirts",
  "thumbnail": "https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/thumbnail.png",
  "dimensions": {
    "depth": 20.88,
    "width": 17.25,
    "height": 27.31
  },
  "description": "The Blue & Black Check Shirt is a stylish and comfortable men\'s shirt featuring a classic check pattern. Made from high-quality fabric, it\'s suitable for both casual and semi-formal occasions.",
  "returnPolicy": "7 days return policy",
  "availabilityStatus": "In Stock",
  "discountPercentage": 1.41,
  "shippingInformation": "Ships in 1 month",
  "warrantyInformation": "No warranty",
  "minimumOrderQuantity": 4
}', 1, TRUE, '2024-12-04 18:39:43', '2024-12-04 18:39:43');


INSERT INTO orders (order_id, user_id, order_placed, shipping, total, created_at, updated_at, status) VALUES
('000b92d9-06a7-464e-9dbe-fde32bddd06c', 
  2, 
  '2024-12-03 18:44:12', 
  '{
    "dates": "Wednesday 11th Dec - Friday 13th Dec",
    "price": 3.99,
    "method": "standard",
    "range_to": "2024-12-13 18:43:57",
    "range_from": "2024-12-11 18:43:57",
    "description": "Delivery within 5-7 working days"
  }', 
  89.97, 
  '2024-12-03 18:44:13', 
  '2024-12-03 18:44:13', 
  'pending'),
('ce2be850-57ed-4922-94f6-0375e255ad1d', 
  2, 
  '2024-11-28 15:29:27', 
  '{
    "dates": "Friday 6th Dec - Tuesday 10th Dec",
    "price": 3.99,
    "method":"standard", 
    "range_to": "2024-12-10 15:29:23",
    "range_from": "2024-12-06 15:29:23",
    "description": "Delivery within 5-7 working days"
  }', 
  137.95, 
  '2024-11-28 15:29:27', 
  '2024-11-28 15:29:27', 
  'pending'),
('d4e84805-f0f1-4281-b426-7748eb40ce59', 
  2, 
  '2024-11-29 17:53:48', 
  '{
    "dates": "Monday 9th Dec - Wednesday 11th Dec",
    "price": 3.99,
    "method": "standard",
    "range_to": "2024-12-11 17:53:44",
    "range_from": "2024-12-09 17:53:44",
    "description": "Delivery within 5-7 working days"
  }', 
  5287.92, 
  '2024-11-29 17:53:48', 
  '2024-11-29 17:53:48', 
  'pending'),
('e6f3a806-afc2-427d-8c56-0b97a5563d99', 
  2, 
  '2024-11-28 15:44:14', 
  '{"dates":"Tuesday 3rd Dec - Wednesday 4th Dec","price":7.99,"method":"express","range_to":"2024-12-04T15:44:10.169Z","range_from":"2024-12-03T15:44:10.169Z","description":"Delivery within 2-3 working days"}', 
  146.46, 
  '2024-11-28 15:44:14', 
  '2024-11-29 17:29:12', 
  'cancelled');


INSERT INTO order_items 
(order_item_id, order_id, product_data, quantity, is_selected, created_at, updated_at)
VALUES
('0fc14493-6c1b-4518-9f44-af8a1bc149b9', 'd4e84805-f0f1-4281-b426-7748eb40ce59', 
'{
  "id": 6,
  "sku": "DZM2JQZE",
  "meta": {
    "qrCode": "https://assets.dummyjson.com/public/qr-code.png",
    "barcode": "2210136215089",
    "created_at": "2024-05-23 08:56:21",
    "updated_at": "2024-05-23 08:56:21"
  },
  "tags": ["fragrances", "perfumes"],
  "brand": "Calvin Klein",
  "price": 49.99,
  "stock": 17,
  "title": "Calvin Klein CK One",
  "images": [
    "https://cdn.dummyjson.com/products/images/fragrances/Calvin%20Klein%20CK%20One/1.png",
    "https://cdn.dummyjson.com/products/images/fragrances/Calvin%20Klein%20CK%20One/2.png",
    "https://cdn.dummyjson.com/products/images/fragrances/Calvin%20Klein%20CK%20One/3.png"
  ],
  "rating": 4.85,
  "weight": 5,
  "reviews": [
    {
      "date": "2024-05-23 08:56:21",
      "rating": 5,
      "comment": "Great value for money!",
      "reviewerName": "Sophia Brown",
      "reviewerEmail": "sophia.brown@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 3,
      "comment": "Very disappointed!",
      "reviewerName": "Madison Collins",
      "reviewerEmail": "madison.collins@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 1,
      "comment": "Poor quality!",
      "reviewerName": "Maya Reed",
      "reviewerEmail": "maya.reed@x.dummyjson.com"
    }
  ],
  "category": "fragrances",
  "thumbnail": "https://cdn.dummyjson.com/products/images/fragrances/Calvin%20Klein%20CK%20One/thumbnail.png",
  "dimensions": {
    "depth": 6.81,
    "width": 11.53,
    "height": 14.44
  },
  "description": "CK One by Calvin Klein is a classic unisex fragrance, known for its fresh and clean scent. It\'s a versatile fragrance suitable for everyday wear.",
  "returnPolicy": "No return policy",
  "availabilityStatus": "In Stock",
  "discountPercentage": 0.32,
  "shippingInformation": "Ships overnight",
  "warrantyInformation": "5 year warranty",
  "minimumOrderQuantity": 20
}', 1, true, '2024-11-29 17:53:48', '2024-11-29 17:53:48'),

('32307eb4-c6e1-4439-ae56-f1edfc5665f1', 'd4e84805-f0f1-4281-b426-7748eb40ce59',
'{
  "id": 3,
  "sku": "9EN8WLT2",
  "meta": {
    "qrCode": "https://assets.dummyjson.com/public/qr-code.png",
    "barcode": "0516267971277",
    "created_at": "2024-05-23 08:56:21",
    "updated_at": "2024-05-23 08:56:21"
  },
  "tags": ["beauty", "face powder"],
  "brand": "Velvet Touch",
  "price": 14.99,
  "stock": 59,
  "title": "Powder Canister",
  "images": ["https://cdn.dummyjson.com/products/images/beauty/Powder%20Canister/1.png"],
  "rating": 3.82,
  "weight": 8,
  "reviews": [
    {
      "date": "2024-05-23 08:56:21",
      "rating": 5,
      "comment": "Very happy with my purchase!",
      "reviewerName": "Ethan Thompson",
      "reviewerEmail": "ethan.thompson@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 4,
      "comment": "Great value for money!",
      "reviewerName": "Levi Hicks",
      "reviewerEmail": "levi.hicks@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 5,
      "comment": "Highly impressed!",
      "reviewerName": "Hazel Gardner",
      "reviewerEmail": "hazel.gardner@x.dummyjson.com"
    }
  ],
  "category": "beauty",
  "thumbnail": "https://cdn.dummyjson.com/products/images/beauty/Powder%20Canister/thumbnail.png",
  "dimensions": {
    "depth": 11.07,
    "width": 24.16,
    "height": 10.7
  },
  "description": "The Powder Canister is a finely milled setting powder designed to set makeup and control shine. With a lightweight and translucent formula, it provides a smooth and matte finish.",
  "returnPolicy": "60 days return policy",
  "availabilityStatus": "In Stock",
  "discountPercentage": 18.14,
  "shippingInformation": "Ships in 1-2 business days",
  "warrantyInformation": "2 year warranty",
  "minimumOrderQuantity": 25
}', 1, true, '2024-11-29 17:53:48', '2024-11-29 17:53:48'),

('47b1ce82-beca-49ab-96af-89fd83614442', 'e6f3a806-afc2-427d-8c56-0b97a5563d99',
'{
  "id": 92,
  "sku": "1SFJZOT2",
  "meta": {
    "qrCode": "https://assets.dummyjson.com/public/qr-code.png",
    "barcode": "0839375107303",
    "created_at": "2024-05-23 08:56:21",
    "updated_at": "2024-05-23 08:56:21"
  },
  "tags": ["footwear", "casual shoes"],
  "brand": "Off White",
  "price": 109.99,
  "stock": 75,
  "title": "Sports Sneakers Off White Red",
  "images": [
    "https://cdn.dummyjson.com/products/images/mens-shoes/Sports%20Sneakers%20Off%20White%20Red/1.png",
    "https://cdn.dummyjson.com/products/images/mens-shoes/Sports%20Sneakers%20Off%20White%20Red/2.png",
    "https://cdn.dummyjson.com/products/images/mens-shoes/Sports%20Sneakers%20Off%20White%20Red/3.png",
    "https://cdn.dummyjson.com/products/images/mens-shoes/Sports%20Sneakers%20Off%20White%20Red/4.png"
  ],
  "rating": 2.95,
  "weight": 4,
  "reviews": [
    {
      "date": "2024-05-23 08:56:21",
      "rating": 5,
      "comment": "Fast shipping!",
      "reviewerName": "Madeline Simpson",
      "reviewerEmail": "madeline.simpson@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 5,
      "comment": "Awesome product!",
      "reviewerName": "Mateo Nguyen",
      "reviewerEmail": "mateo.nguyen@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 4,
      "comment": "Very pleased!",
      "reviewerName": "Ethan Fletcher",
      "reviewerEmail": "ethan.fletcher@x.dummyjson.com"
    }
  ],
  "category": "mens-shoes",
  "thumbnail": "https://cdn.dummyjson.com/products/images/mens-shoes/Sports%20Sneakers%20Off%20White%20Red/thumbnail.png",
  "dimensions": {
    "depth": 28.56,
    "width": 18.32,
    "height": 19.34
  },
  "description": "Another variant of the Sports Sneakers in Off White Red, featuring a unique design. These sneakers offer style and comfort for casual occasions.",
  "returnPolicy": "No return policy",
  "availabilityStatus": "In Stock",
  "discountPercentage": 17.22,
  "shippingInformation": "Ships in 1 week",
  "warrantyInformation": "No warranty",
  "minimumOrderQuantity": 3
}', 1, true, '2024-11-28 15:44:14', '2024-11-28 15:44:14'),

('6bc2eb8f-af06-4fca-9c39-a9d6a50ee588', 'd4e84805-f0f1-4281-b426-7748eb40ce59',
'{
  "id": 4,
  "sku": "O5IF1NTA",
  "meta": {
    "qrCode": "https://assets.dummyjson.com/public/qr-code.png",
    "barcode": "9444582199406",
    "created_at": "2024-05-23 08:56:21",
    "updated_at": "2024-05-23 08:56:21"
  },
  "tags": ["beauty", "lipstick"],
  "brand": "Chic Cosmetics",
  "price": 12.99,
  "stock": 68,
  "title": "Red Lipstick",
  "images": ["https://cdn.dummyjson.com/products/images/beauty/Red%20Lipstick/1.png"],
  "rating": 2.51,
  "weight": 5,
  "reviews": [
    {
      "date": "2024-05-23 08:56:21",
      "rating": 5,
      "comment": "Great product!",
      "reviewerName": "Leo Rivera",
      "reviewerEmail": "leo.rivera@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 4,
      "comment": "Very pleased!",
      "reviewerName": "Oscar Powers",
      "reviewerEmail": "oscar.powers@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 5,
      "comment": "Very pleased!",
      "reviewerName": "Carter Rivera",
      "reviewerEmail": "carter.rivera@x.dummyjson.com"
    }
  ],
  "category": "beauty",
  "thumbnail": "https://cdn.dummyjson.com/products/images/beauty/Red%20Lipstick/thumbnail.png",
  "dimensions": {
    "depth": 14.6,
    "width": 14.37,
    "height": 13.94
  },
  "description": "The Red Lipstick is a classic and bold choice for adding a pop of color to your lips. With a creamy and pigmented formula, it provides a vibrant and long-lasting finish.",
  "returnPolicy": "90 days return policy",
  "availabilityStatus": "In Stock",
  "discountPercentage": 19.03,
  "shippingInformation": "Ships in 2 weeks",
  "warrantyInformation": "Lifetime warranty",
  "minimumOrderQuantity": 6
}', 1, true, '2024-11-29 17:53:48', '2024-11-29 17:53:48'),

('6ed1215b-5d29-4ea2-bbd3-6f6c6bcd3dab', 'e6f3a806-afc2-427d-8c56-0b97a5563d99',
'{
  "id": 21,
  "sku": "6KGF2K6Z",
  "meta": {
    "qrCode": "https://assets.dummyjson.com/public/qr-code.png",
    "barcode": "2597004869708",
    "created_at": "2024-05-23 08:56:21",
    "updated_at": "2024-05-23 08:56:21"
  },
  "tags": ["vegetables"],
  "price": 1.49,
  "stock": 22,
  "title": "Cucumber",
  "images": ["https://cdn.dummyjson.com/products/images/groceries/Cucumber/1.png"],
  "rating": 4.71,
  "weight": 9,
  "reviews": [
    {
      "date": "2024-05-23 08:56:21",
      "rating": 4,
      "comment": "Very satisfied!",
      "reviewerName": "Elijah Hill",
      "reviewerEmail": "elijah.hill@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 5,
      "comment": "Fast shipping!",
      "reviewerName": "Liam Garcia",
      "reviewerEmail": "liam.garcia@x.dummyjson.com"
    },
    {
      "date": "2024-05-23 08:56:21",
      "rating": 4,
      "comment": "Excellent quality!",
      "reviewerName": "Ella Cook",
      "reviewerEmail": "ella.cook@x.dummyjson.com"
    }
  ],
  "category": "groceries",
  "thumbnail": "https://cdn.dummyjson.com/products/images/groceries/Cucumber/thumbnail.png",
  "dimensions": {
    "depth": 8.18,
    "width": 11.04,
    "height": 20.5
  },
    "description": "Crisp and hydrating cucumbers, ideal for salads, snacks, or as a refreshing side.",
    "returnPolicy": "30 days return policy",
    "availabilityStatus": "In Stock",
    "discountPercentage": 11.44,
    "shippingInformation": "Ships overnight",
    "warrantyInformation": "5 year warranty",
    "minimumOrderQuantity": 7
  }',
  1, true, '2024-11-28 15:44:14', '2024-11-28 15:44:14'),

('7790e788-b722-43ae-99a7-a001f3047fed', '000b92d9-06a7-464e-9dbe-fde32bddd06c',
  '{
    "id": 83,
    "sku": "6RJDTVCU",
    "meta": {
      "qrCode": "https://assets.dummyjson.com/public/qr-code.png",
      "barcode": "8840720880947", 
      "created_at": "2024-05-23 08:56:21",
      "updated_at": "2024-05-23 08:56:21"
    },
    "tags": [
      "clothing",
      "men\'s shirts"
    ],
    "brand": "Fashion Trends",
    "price": 29.99,
    "stock": 44, 
    "title": "Blue & Black Check Shirt",
    "images": [
      "https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/1.png",
      "https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/2.png",
      "https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/3.png",
      "https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/4.png"
    ],
    "rating": 4.19,
    "weight": 6,
    "reviews": [
      {
        "date": "2024-05-23 08:56:21", 
        "rating": 4,
        "comment": "Highly recommended!",
        "reviewerName": "Mason Parker",
        "reviewerEmail": "mason.parker@x.dummyjson.com"
      }, {
        "date": "2024-05-23 08:56:21", 
        "rating": 5,
        "comment": "Highly impressed!", 
        "reviewerName": "Avery Perez", 
        "reviewerEmail": "avery.perez@x.dummyjson.com"
      }, {
        "date": "2024-05-23 08:56:21", 
        "rating": 5,
        "comment": "Fast shipping!", 
        "reviewerName": "Nathan Reed",
        "reviewerEmail": "nathan.reed@x.dummyjson.com"
      }
    ],
    "category": "mens-shirts",
    "thumbnail": "https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/thumbnail.png",
    "dimensions": {
      "depth": 20.88,
      "width": 17.25,
      "height": 27.31
    },
    "description": "The Blue & Black Check Shirt is a stylish and comfortable men\'s shirt featuring a classic check pattern. Made from high-quality fabric, it\'s suitable for both casual and semi-formal occasions.", 
    "returnPolicy": "7 days return policy",
    "availabilityStatus": "In Stock",
    "discountPercentage": 1.41,
    "shippingInformation": "Ships in 1 month", 
    "warrantyInformation": "No warranty",
    "minimumOrderQuantity": 4
  }', 1, true, '2024-12-03 18:44:13', '2024-12-03 18:44:13'),

8448f804-3984-4938-a455-fa727287e9fb,d4e84805-f0f1-4281-b426-7748eb40ce59,"{""id"":14,""sku"":""RKHVJ4FE"",""meta"":{""qrCode"":""https://assets.dummyjson.com/public/qr-code.png"",""barcode"":""0726316339746"",""created_at"":""2024-05-23T08:56:21.620Z"",""updated_at"":""2024-05-23T08:56:21.620Z""},""tags"":[""furniture"",""office chairs""],""brand"":""Knoll"",""price"":499.99,""stock"":47,""title"":""Knoll Saarinen Executive Conference Chair"",""images"":[""https://cdn.dummyjson.com/products/images/furniture/Knoll%20Saarinen%20Executive%20Conference%20Chair/1.png"",""https://cdn.dummyjson.com/products/images/furniture/Knoll%20Saarinen%20Executive%20Conference%20Chair/2.png"",""https://cdn.dummyjson.com/products/images/furniture/Knoll%20Saarinen%20Executive%20Conference%20Chair/3.png""],""rating"":4.11,""weight"":3,""reviews"":[{""date"":""2024-05-23T08:56:21.620Z"",""rating"":4,""comment"":""Very happy with my purchase!"",""reviewerName"":""Leah Gutierrez"",""reviewerEmail"":""leah.gutierrez@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.620Z"",""rating"":4,""comment"":""Would buy again!"",""reviewerName"":""Nolan Gonzalez"",""reviewerEmail"":""nolan.gonzalez@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.620Z"",""rating"":2,""comment"":""Waste of money!"",""reviewerName"":""Stella Morris"",""reviewerEmail"":""stella.morris@x.dummyjson.com""}],""category"":""furniture"",""thumbnail"":""https://cdn.dummyjson.com/products/images/furniture/Knoll%20Saarinen%20Executive%20Conference%20Chair/thumbnail.png"",""dimensions"":{""depth"":29.07,""width"":16.59,""height"":21.46},""description"":""The Knoll Saarinen Executive Conference Chair is a modern and ergonomic chair, perfect for your office or conference room with its timeless design."",""returnPolicy"":""30 days return policy"",""availabilityStatus"":""In Stock"",""discountPercentage"":15.23,""shippingInformation"":""Ships in 3-5 business days"",""warrantyInformation"":""Lifetime warranty"",""minimumOrderQuantity"":5}",1,true,2024-11-29 17:53:48.304+00,2024-11-29 17:53:48.304+00
893db160-b1a4-4925-b224-5fb8b0912812,000b92d9-06a7-464e-9dbe-fde32bddd06c,"{""id"":85,""sku"":""TWL7HRBB"",""meta"":{""qrCode"":""https://assets.dummyjson.com/public/qr-code.png"",""barcode"":""9383795178668"",""created_at"":""2024-05-23T08:56:21.623Z"",""updated_at"":""2024-05-23T08:56:21.623Z""},""tags"":[""clothing"",""men's shirts""],""brand"":""Classic Wear"",""price"":34.99,""stock"":65,""title"":""Man Plaid Shirt"",""images"":[""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/1.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/2.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/3.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/4.png""],""rating"":3.66,""weight"":1,""reviews"":[{""date"":""2024-05-23T08:56:21.623Z"",""rating"":3,""comment"":""Would not buy again!"",""reviewerName"":""Nora Mills"",""reviewerEmail"":""nora.mills@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.623Z"",""rating"":1,""comment"":""Very disappointed!"",""reviewerName"":""Addison Ward"",""reviewerEmail"":""addison.ward@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.623Z"",""rating"":4,""comment"":""Awesome product!"",""reviewerName"":""Emily Brown"",""reviewerEmail"":""emily.brown@x.dummyjson.com""}],""category"":""mens-shirts"",""thumbnail"":""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/thumbnail.png"",""dimensions"":{""depth"":7.77,""width"":29.56,""height"":29.84},""description"":""The Man Plaid Shirt is a timeless and versatile men's shirt with a classic plaid pattern. Its comfortable fit and casual style make it a wardrobe essential for various occasions."",""returnPolicy"":""30 days return policy"",""availabilityStatus"":""In Stock"",""discountPercentage"":17.53,""shippingInformation"":""Ships in 1 week"",""warrantyInformation"":""Lifetime warranty"",""minimumOrderQuantity"":1}",1,true,2024-12-03 18:44:13.042+00,2024-12-03 18:44:13.042+00
8e18ee1e-02be-4ff0-9655-cfbe5866f315,e6f3a806-afc2-427d-8c56-0b97a5563d99,"{""id"":137,""sku"":""QLDVAXMJ"",""meta"":{""qrCode"":""https://assets.dummyjson.com/public/qr-code.png"",""barcode"":""2510402973927"",""created_at"":""2024-05-23T08:56:21.625Z"",""updated_at"":""2024-05-23T08:56:21.625Z""},""tags"":[""sports equipment"",""american football""],""price"":19.99,""stock"":48,""title"":""American Football"",""images"":[""https://cdn.dummyjson.com/products/images/sports-accessories/American%20Football/1.png""],""rating"":3.78,""weight"":9,""reviews"":[{""date"":""2024-05-23T08:56:21.625Z"",""rating"":4,""comment"":""Excellent quality!"",""reviewerName"":""Jaxon Barnes"",""reviewerEmail"":""jaxon.barnes@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.625Z"",""rating"":4,""comment"":""Great product!"",""reviewerName"":""Aurora Lawson"",""reviewerEmail"":""aurora.lawson@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.625Z"",""rating"":4,""comment"":""Awesome product!"",""reviewerName"":""Evan Reed"",""reviewerEmail"":""evan.reed@x.dummyjson.com""}],""category"":""sports-accessories"",""thumbnail"":""https://cdn.dummyjson.com/products/images/sports-accessories/American%20Football/thumbnail.png"",""dimensions"":{""depth"":26.86,""width"":9.74,""height"":23.16},""description"":""The American Football is a classic ball used in American football games. It is designed for throwing and catching, making it an essential piece of equipment for the sport."",""returnPolicy"":""30 days return policy"",""availabilityStatus"":""In Stock"",""discountPercentage"":10.28,""shippingInformation"":""Ships in 2 weeks"",""warrantyInformation"":""1 month warranty"",""minimumOrderQuantity"":23}",1,true,2024-11-28 15:44:14.416+00,2024-11-28 15:44:14.416+00
a455bf5d-cfba-447c-9c57-2de71f2537a5,ce2be850-57ed-4922-94f6-0375e255ad1d,"{""id"":85,""sku"":""TWL7HRBB"",""meta"":{""qrCode"":""https://assets.dummyjson.com/public/qr-code.png"",""barcode"":""9383795178668"",""created_at"":""2024-05-23T08:56:21.623Z"",""updated_at"":""2024-05-23T08:56:21.623Z""},""tags"":[""clothing"",""men's shirts""],""brand"":""Classic Wear"",""price"":34.99,""stock"":65,""title"":""Man Plaid Shirt"",""images"":[""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/1.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/2.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/3.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/4.png""],""rating"":3.66,""weight"":1,""reviews"":[{""date"":""2024-05-23T08:56:21.623Z"",""rating"":3,""comment"":""Would not buy again!"",""reviewerName"":""Nora Mills"",""reviewerEmail"":""nora.mills@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.623Z"",""rating"":1,""comment"":""Very disappointed!"",""reviewerName"":""Addison Ward"",""reviewerEmail"":""addison.ward@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.623Z"",""rating"":4,""comment"":""Awesome product!"",""reviewerName"":""Emily Brown"",""reviewerEmail"":""emily.brown@x.dummyjson.com""}],""category"":""mens-shirts"",""thumbnail"":""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Plaid%20Shirt/thumbnail.png"",""dimensions"":{""depth"":7.77,""width"":29.56,""height"":29.84},""description"":""The Man Plaid Shirt is a timeless and versatile men's shirt with a classic plaid pattern. Its comfortable fit and casual style make it a wardrobe essential for various occasions."",""returnPolicy"":""30 days return policy"",""availabilityStatus"":""In Stock"",""discountPercentage"":17.53,""shippingInformation"":""Ships in 1 week"",""warrantyInformation"":""Lifetime warranty"",""minimumOrderQuantity"":1}",1,true,2024-11-28 15:29:27.799+00,2024-11-28 15:29:27.799+00
a806b780-330b-43ea-b869-3ff3bf862f8c,d4e84805-f0f1-4281-b426-7748eb40ce59,"{""id"":120,""sku"":""31IK2JUC"",""meta"":{""qrCode"":""https://assets.dummyjson.com/public/qr-code.png"",""barcode"":""7128996084231"",""created_at"":""2024-05-23T08:56:21.625Z"",""updated_at"":""2024-05-23T08:56:21.625Z""},""tags"":[""personal care"",""body lotion""],""brand"":""Vaseline"",""price"":9.99,""stock"":54,""title"":""Vaseline Men Body and Face Lotion"",""images"":[""https://cdn.dummyjson.com/products/images/skin-care/Vaseline%20Men%20Body%20and%20Face%20Lotion/1.png"",""https://cdn.dummyjson.com/products/images/skin-care/Vaseline%20Men%20Body%20and%20Face%20Lotion/2.png"",""https://cdn.dummyjson.com/products/images/skin-care/Vaseline%20Men%20Body%20and%20Face%20Lotion/3.png""],""rating"":3.97,""weight"":10,""reviews"":[{""date"":""2024-05-23T08:56:21.625Z"",""rating"":5,""comment"":""Great product!"",""reviewerName"":""Carter Rivera"",""reviewerEmail"":""carter.rivera@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.625Z"",""rating"":4,""comment"":""Awesome product!"",""reviewerName"":""Jackson Morales"",""reviewerEmail"":""jackson.morales@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.625Z"",""rating"":4,""comment"":""Very happy with my purchase!"",""reviewerName"":""Grace Green"",""reviewerEmail"":""grace.green@x.dummyjson.com""}],""category"":""skin-care"",""thumbnail"":""https://cdn.dummyjson.com/products/images/skin-care/Vaseline%20Men%20Body%20and%20Face%20Lotion/thumbnail.png"",""dimensions"":{""depth"":15.49,""width"":28.39,""height"":25.58},""description"":""Vaseline Men Body and Face Lotion is a specially formulated lotion designed to provide long-lasting moisture to men's skin. It absorbs quickly and helps keep the skin hydrated and healthy."",""returnPolicy"":""60 days return policy"",""availabilityStatus"":""In Stock"",""discountPercentage"":11.63,""shippingInformation"":""Ships in 1 month"",""warrantyInformation"":""5 year warranty"",""minimumOrderQuantity"":48}",1,true,2024-11-29 17:53:48.304+00,2024-11-29 17:53:48.304+00
a832bdff-3f5b-48ed-b41d-06691aedaefa,e6f3a806-afc2-427d-8c56-0b97a5563d99,"{""id"":140,""sku"":""EMF68NH8"",""meta"":{""qrCode"":""https://assets.dummyjson.com/public/qr-code.png"",""barcode"":""0365979467747"",""created_at"":""2024-05-23T08:56:21.625Z"",""updated_at"":""2024-05-23T08:56:21.625Z""},""tags"":[""sports equipment"",""basketball""],""price"":14.99,""stock"":100,""title"":""Basketball"",""images"":[""https://cdn.dummyjson.com/products/images/sports-accessories/Basketball/1.png""],""rating"":4.15,""weight"":10,""reviews"":[{""date"":""2024-05-23T08:56:21.625Z"",""rating"":2,""comment"":""Not worth the price!"",""reviewerName"":""Luna Russell"",""reviewerEmail"":""luna.russell@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.625Z"",""rating"":5,""comment"":""Highly impressed!"",""reviewerName"":""Asher Scott"",""reviewerEmail"":""asher.scott@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.625Z"",""rating"":4,""comment"":""Awesome product!"",""reviewerName"":""Max Russell"",""reviewerEmail"":""max.russell@x.dummyjson.com""}],""category"":""sports-accessories"",""thumbnail"":""https://cdn.dummyjson.com/products/images/sports-accessories/Basketball/thumbnail.png"",""dimensions"":{""depth"":16.25,""width"":15.68,""height"":23.18},""description"":""The Basketball is a standard ball used in basketball games. It is designed for dribbling, shooting, and passing in the game of basketball, suitable for both indoor and outdoor play."",""returnPolicy"":""30 days return policy"",""availabilityStatus"":""In Stock"",""discountPercentage"":18.05,""shippingInformation"":""Ships in 1 month"",""warrantyInformation"":""3 months warranty"",""minimumOrderQuantity"":2}",1,true,2024-11-28 15:44:14.416+00,2024-11-28 15:44:14.416+00
ab8b0fce-ab27-46c1-a9a1-43a0439a835a,ce2be850-57ed-4922-94f6-0375e255ad1d,"{""id"":87,""sku"":""L902AU9L"",""meta"":{""qrCode"":""https://assets.dummyjson.com/public/qr-code.png"",""barcode"":""1360512025227"",""created_at"":""2024-05-23T08:56:21.623Z"",""updated_at"":""2024-05-23T08:56:21.623Z""},""tags"":[""clothing"",""men's shirts""],""brand"":""Urban Chic"",""price"":27.99,""stock"":69,""title"":""Men Check Shirt"",""images"":[""https://cdn.dummyjson.com/products/images/mens-shirts/Men%20Check%20Shirt/1.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Men%20Check%20Shirt/2.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Men%20Check%20Shirt/3.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Men%20Check%20Shirt/4.png""],""rating"":2.9,""weight"":1,""reviews"":[{""date"":""2024-05-23T08:56:21.623Z"",""rating"":1,""comment"":""Would not buy again!"",""reviewerName"":""Elena Long"",""reviewerEmail"":""elena.long@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.623Z"",""rating"":1,""comment"":""Very dissatisfied!"",""reviewerName"":""Mason Parker"",""reviewerEmail"":""mason.parker@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.623Z"",""rating"":4,""comment"":""Great product!"",""reviewerName"":""Sofia Mitchell"",""reviewerEmail"":""sofia.mitchell@x.dummyjson.com""}],""category"":""mens-shirts"",""thumbnail"":""https://cdn.dummyjson.com/products/images/mens-shirts/Men%20Check%20Shirt/thumbnail.png"",""dimensions"":{""depth"":20.73,""width"":18.43,""height"":6.96},""description"":""The Men Check Shirt is a classic and versatile shirt featuring a stylish check pattern. Suitable for various occasions, it adds a smart and polished touch to your wardrobe."",""returnPolicy"":""90 days return policy"",""availabilityStatus"":""In Stock"",""discountPercentage"":14.21,""shippingInformation"":""Ships in 1 month"",""warrantyInformation"":""3 year warranty"",""minimumOrderQuantity"":8}",1,true,2024-11-28 15:29:27.799+00,2024-11-28 15:29:27.799+00
bfa3840d-1de3-4376-a896-5b35cf56fe55,ce2be850-57ed-4922-94f6-0375e255ad1d,"{""id"":83,""sku"":""6RJDTVCU"",""meta"":{""qrCode"":""https://assets.dummyjson.com/public/qr-code.png"",""barcode"":""8840720880947"",""created_at"":""2024-05-23T08:56:21.623Z"",""updated_at"":""2024-05-23T08:56:21.623Z""},""tags"":[""clothing"",""men's shirts""],""brand"":""Fashion Trends"",""price"":29.99,""stock"":44,""title"":""Blue & Black Check Shirt"",""images"":[""https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/1.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/2.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/3.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/4.png""],""rating"":4.19,""weight"":6,""reviews"":[{""date"":""2024-05-23T08:56:21.623Z"",""rating"":4,""comment"":""Highly recommended!"",""reviewerName"":""Mason Parker"",""reviewerEmail"":""mason.parker@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.623Z"",""rating"":5,""comment"":""Highly impressed!"",""reviewerName"":""Avery Perez"",""reviewerEmail"":""avery.perez@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.623Z"",""rating"":5,""comment"":""Fast shipping!"",""reviewerName"":""Nathan Reed"",""reviewerEmail"":""nathan.reed@x.dummyjson.com""}],""category"":""mens-shirts"",""thumbnail"":""https://cdn.dummyjson.com/products/images/mens-shirts/Blue%20&%20Black%20Check%20Shirt/thumbnail.png"",""dimensions"":{""depth"":20.88,""width"":17.25,""height"":27.31},""description"":""The Blue & Black Check Shirt is a stylish and comfortable men's shirt featuring a classic check pattern. Made from high-quality fabric, it's suitable for both casual and semi-formal occasions."",""returnPolicy"":""7 days return policy"",""availabilityStatus"":""In Stock"",""discountPercentage"":1.41,""shippingInformation"":""Ships in 1 month"",""warrantyInformation"":""No warranty"",""minimumOrderQuantity"":4}",1,true,2024-11-28 15:29:27.799+00,2024-11-28 15:29:27.799+00
c4cb0cc5-4fb4-4d39-a8c7-9bc68653e187,d4e84805-f0f1-4281-b426-7748eb40ce59,"{""id"":12,""sku"":""LUU95CQP"",""meta"":{""qrCode"":""https://assets.dummyjson.com/public/qr-code.png"",""barcode"":""0426785817074"",""created_at"":""2024-05-23T08:56:21.620Z"",""updated_at"":""2024-05-23T08:56:21.620Z""},""tags"":[""furniture"",""sofas""],""brand"":""Annibale Colombo"",""price"":2499.99,""stock"":16,""title"":""Annibale Colombo Sofa"",""images"":[""https://cdn.dummyjson.com/products/images/furniture/Annibale%20Colombo%20Sofa/1.png"",""https://cdn.dummyjson.com/products/images/furniture/Annibale%20Colombo%20Sofa/2.png"",""https://cdn.dummyjson.com/products/images/furniture/Annibale%20Colombo%20Sofa/3.png""],""rating"":3.08,""weight"":3,""reviews"":[{""date"":""2024-05-23T08:56:21.620Z"",""rating"":5,""comment"":""Very satisfied!"",""reviewerName"":""Tyler Davis"",""reviewerEmail"":""tyler.davis@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.620Z"",""rating"":5,""comment"":""Excellent quality!"",""reviewerName"":""Hannah Robinson"",""reviewerEmail"":""hannah.robinson@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.620Z"",""rating"":3,""comment"":""Waste of money!"",""reviewerName"":""Madison Collins"",""reviewerEmail"":""madison.collins@x.dummyjson.com""}],""category"":""furniture"",""thumbnail"":""https://cdn.dummyjson.com/products/images/furniture/Annibale%20Colombo%20Sofa/thumbnail.png"",""dimensions"":{""depth"":25.81,""width"":20.97,""height"":19.11},""description"":""The Annibale Colombo Sofa is a sophisticated and comfortable seating option, featuring exquisite design and premium upholstery for your living room."",""returnPolicy"":""7 days return policy"",""availabilityStatus"":""In Stock"",""discountPercentage"":18.54,""shippingInformation"":""Ships overnight"",""warrantyInformation"":""1 month warranty"",""minimumOrderQuantity"":1}",1,true,2024-11-29 17:53:48.304+00,2024-11-29 17:53:48.304+00
cb6fa172-5adf-4aad-945d-17b704820e49,ce2be850-57ed-4922-94f6-0375e255ad1d,"{""id"":84,""sku"":""QA703Y60"",""meta"":{""qrCode"":""https://assets.dummyjson.com/public/qr-code.png"",""barcode"":""3072645939073"",""created_at"":""2024-05-23T08:56:21.623Z"",""updated_at"":""2024-05-23T08:56:21.623Z""},""tags"":[""clothing"",""men's t-shirts""],""brand"":""Gigabyte"",""price"":24.99,""stock"":64,""title"":""Gigabyte Aorus Men Tshirt"",""images"":[""https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/1.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/2.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/3.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/4.png""],""rating"":4.95,""weight"":2,""reviews"":[{""date"":""2024-05-23T08:56:21.623Z"",""rating"":4,""comment"":""Highly recommended!"",""reviewerName"":""Logan Lawson"",""reviewerEmail"":""logan.lawson@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.623Z"",""rating"":4,""comment"":""Great value for money!"",""reviewerName"":""Logan Lawson"",""reviewerEmail"":""logan.lawson@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.623Z"",""rating"":5,""comment"":""Great value for money!"",""reviewerName"":""Oscar Powers"",""reviewerEmail"":""oscar.powers@x.dummyjson.com""}],""category"":""mens-shirts"",""thumbnail"":""https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/thumbnail.png"",""dimensions"":{""depth"":5.66,""width"":8.54,""height"":23.52},""description"":""The Gigabyte Aorus Men Tshirt is a cool and casual shirt for gaming enthusiasts. With the Aorus logo and sleek design, it's perfect for expressing your gaming style."",""returnPolicy"":""30 days return policy"",""availabilityStatus"":""In Stock"",""discountPercentage"":12.6,""shippingInformation"":""Ships in 1 week"",""warrantyInformation"":""1 month warranty"",""minimumOrderQuantity"":4}",1,true,2024-11-28 15:29:27.799+00,2024-11-28 15:29:27.799+00
d7b504fa-3c48-4790-be3e-ea0309e1212a,d4e84805-f0f1-4281-b426-7748eb40ce59,"{""id"":13,""sku"":""OWPLTZYX"",""meta"":{""qrCode"":""https://assets.dummyjson.com/public/qr-code.png"",""barcode"":""2913244159666"",""created_at"":""2024-05-23T08:56:21.620Z"",""updated_at"":""2024-05-23T08:56:21.620Z""},""tags"":[""furniture"",""bedside tables""],""brand"":""Furniture Co."",""price"":299.99,""stock"":16,""title"":""Bedside Table African Cherry"",""images"":[""https://cdn.dummyjson.com/products/images/furniture/Bedside%20Table%20African%20Cherry/1.png"",""https://cdn.dummyjson.com/products/images/furniture/Bedside%20Table%20African%20Cherry/2.png"",""https://cdn.dummyjson.com/products/images/furniture/Bedside%20Table%20African%20Cherry/3.png""],""rating"":4.48,""weight"":10,""reviews"":[{""date"":""2024-05-23T08:56:21.620Z"",""rating"":5,""comment"":""Very happy with my purchase!"",""reviewerName"":""John Doe"",""reviewerEmail"":""john.doe@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.620Z"",""rating"":4,""comment"":""Highly recommended!"",""reviewerName"":""Avery Carter"",""reviewerEmail"":""avery.carter@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.620Z"",""rating"":4,""comment"":""Very pleased!"",""reviewerName"":""Evelyn Sanchez"",""reviewerEmail"":""evelyn.sanchez@x.dummyjson.com""}],""category"":""furniture"",""thumbnail"":""https://cdn.dummyjson.com/products/images/furniture/Bedside%20Table%20African%20Cherry/thumbnail.png"",""dimensions"":{""depth"":24.95,""width"":25.43,""height"":20.2},""description"":""The Bedside Table in African Cherry is a stylish and functional addition to your bedroom, providing convenient storage space and a touch of elegance."",""returnPolicy"":""No return policy"",""availabilityStatus"":""In Stock"",""discountPercentage"":9.58,""shippingInformation"":""Ships in 1-2 business days"",""warrantyInformation"":""6 months warranty"",""minimumOrderQuantity"":5}",1,true,2024-11-29 17:53:48.304+00,2024-11-29 17:53:48.304+00
e8e3e1c0-0689-4f60-a4e0-82b7d23dbca3,d4e84805-f0f1-4281-b426-7748eb40ce59,"{""id"":11,""sku"":""4KMDTZWF"",""meta"":{""qrCode"":""https://assets.dummyjson.com/public/qr-code.png"",""barcode"":""7113807059215"",""created_at"":""2024-05-23T08:56:21.620Z"",""updated_at"":""2024-05-23T08:56:21.620Z""},""tags"":[""furniture"",""beds""],""brand"":""Annibale Colombo"",""price"":1899.99,""stock"":47,""title"":""Annibale Colombo Bed"",""images"":[""https://cdn.dummyjson.com/products/images/furniture/Annibale%20Colombo%20Bed/1.png"",""https://cdn.dummyjson.com/products/images/furniture/Annibale%20Colombo%20Bed/2.png"",""https://cdn.dummyjson.com/products/images/furniture/Annibale%20Colombo%20Bed/3.png""],""rating"":4.14,""weight"":3,""reviews"":[{""date"":""2024-05-23T08:56:21.620Z"",""rating"":4,""comment"":""Great value for money!"",""reviewerName"":""Julian Newton"",""reviewerEmail"":""julian.newton@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.620Z"",""rating"":5,""comment"":""Would buy again!"",""reviewerName"":""Madison Collins"",""reviewerEmail"":""madison.collins@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.620Z"",""rating"":4,""comment"":""Would buy again!"",""reviewerName"":""Clara Berry"",""reviewerEmail"":""clara.berry@x.dummyjson.com""}],""category"":""furniture"",""thumbnail"":""https://cdn.dummyjson.com/products/images/furniture/Annibale%20Colombo%20Bed/thumbnail.png"",""dimensions"":{""depth"":24.47,""width"":28.75,""height"":26.88},""description"":""The Annibale Colombo Bed is a luxurious and elegant bed frame, crafted with high-quality materials for a comfortable and stylish bedroom."",""returnPolicy"":""7 days return policy"",""availabilityStatus"":""In Stock"",""discountPercentage"":0.29,""shippingInformation"":""Ships overnight"",""warrantyInformation"":""2 year warranty"",""minimumOrderQuantity"":1}",1,true,2024-11-29 17:53:48.304+00,2024-11-29 17:53:48.304+00
f6d2566e-c563-4089-a450-edf5ef9d7b02,ce2be850-57ed-4922-94f6-0375e255ad1d,"{""id"":86,""sku"":""KEVGVDTV"",""meta"":{""qrCode"":""https://assets.dummyjson.com/public/qr-code.png"",""barcode"":""8162805546713"",""created_at"":""2024-05-23T08:56:21.623Z"",""updated_at"":""2024-05-23T08:56:21.623Z""},""tags"":[""clothing"",""men's shirts""],""brand"":""Casual Comfort"",""price"":19.99,""stock"":20,""title"":""Man Short Sleeve Shirt"",""images"":[""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Short%20Sleeve%20Shirt/1.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Short%20Sleeve%20Shirt/2.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Short%20Sleeve%20Shirt/3.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Short%20Sleeve%20Shirt/4.png""],""rating"":4.62,""weight"":2,""reviews"":[{""date"":""2024-05-23T08:56:21.623Z"",""rating"":5,""comment"":""Great value for money!"",""reviewerName"":""Caleb Nelson"",""reviewerEmail"":""caleb.nelson@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.623Z"",""rating"":1,""comment"":""Disappointing product!"",""reviewerName"":""Emily Johnson"",""reviewerEmail"":""emily.johnson@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.623Z"",""rating"":4,""comment"":""Great product!"",""reviewerName"":""Mila Hernandez"",""reviewerEmail"":""mila.hernandez@x.dummyjson.com""}],""category"":""mens-shirts"",""thumbnail"":""https://cdn.dummyjson.com/products/images/mens-shirts/Man%20Short%20Sleeve%20Shirt/thumbnail.png"",""dimensions"":{""depth"":27.54,""width"":7.11,""height"":28.63},""description"":""The Man Short Sleeve Shirt is a breezy and stylish option for warm days. With a comfortable fit and short sleeves, it's perfect for a laid-back yet polished look."",""returnPolicy"":""7 days return policy"",""availabilityStatus"":""In Stock"",""discountPercentage"":8.65,""shippingInformation"":""Ships in 2 weeks"",""warrantyInformation"":""5 year warranty"",""minimumOrderQuantity"":1}",1,true,2024-11-28 15:29:27.799+00,2024-11-28 15:29:27.799+00
fb2b3474-14da-4d54-a2c8-c46de06be978,000b92d9-06a7-464e-9dbe-fde32bddd06c,"{""id"":84,""sku"":""QA703Y60"",""meta"":{""qrCode"":""https://assets.dummyjson.com/public/qr-code.png"",""barcode"":""3072645939073"",""created_at"":""2024-05-23T08:56:21.623Z"",""updated_at"":""2024-05-23T08:56:21.623Z""},""tags"":[""clothing"",""men's t-shirts""],""brand"":""Gigabyte"",""price"":24.99,""stock"":64,""title"":""Gigabyte Aorus Men Tshirt"",""images"":[""https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/1.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/2.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/3.png"",""https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/4.png""],""rating"":4.95,""weight"":2,""reviews"":[{""date"":""2024-05-23T08:56:21.623Z"",""rating"":4,""comment"":""Highly recommended!"",""reviewerName"":""Logan Lawson"",""reviewerEmail"":""logan.lawson@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.623Z"",""rating"":4,""comment"":""Great value for money!"",""reviewerName"":""Logan Lawson"",""reviewerEmail"":""logan.lawson@x.dummyjson.com""},{""date"":""2024-05-23T08:56:21.623Z"",""rating"":5,""comment"":""Great value for money!"",""reviewerName"":""Oscar Powers"",""reviewerEmail"":""oscar.powers@x.dummyjson.com""}],""category"":""mens-shirts"",""thumbnail"":""https://cdn.dummyjson.com/products/images/mens-shirts/Gigabyte%20Aorus%20Men%20Tshirt/thumbnail.png"",""dimensions"":{""depth"":5.66,""width"":8.54,""height"":23.52},""description"":""The Gigabyte Aorus Men Tshirt is a cool and casual shirt for gaming enthusiasts. With the Aorus logo and sleek design, it's perfect for expressing your gaming style."",""returnPolicy"":""30 days return policy"",""availabilityStatus"":""In Stock"",""discountPercentage"":12.6,""shippingInformation"":""Ships in 1 week"",""warrantyInformation"":""1 month warranty"",""minimumOrderQuantity"":4}",1,true,2024-12-03 18:44:13.042+00,2024-12-03 18:44:13.042+00

INSERT INTO payment_methods (payment_method_id, user_id, bank, card_type, card_account, card_number, cardholder_name, start_date, end_date, cvv, status) VALUES
(1, 1, 'Barclays', 'VISA', 'Debit', '4532012345673773', 'Testy McTestFace', '01/23', '01/28', '397', 'valid'),
(2, 1, 'HSBC', 'Mastercard', 'Credit', '5412345678901234', 'Testy McTestFace', '03/24', '03/29', '456', 'valid'),
(3, 1, 'NatWest', 'VISA', 'Credit', '4123456789012345', 'Testy McTestFace', '06/24', '06/29', '789', 'valid'),
(4, 1, 'Lloyds Bank', 'Mastercard', 'Debit', '5234567890123456', 'Testy McTestFace', '12/19', '12/28', '234', 'default'),
(5, 1, 'Santander', 'VISA', 'Credit', '4345678901234567', 'Testy McTestFace', '09/20', '09/29', '567', 'valid'),
(6, 1, 'Halifax', 'Mastercard', 'Debit', '5345678901234567', 'Testy McTestFace', '03/19', '03/22', '890', 'expired'),
(7, 1, 'RBS', 'VISA', 'Credit', '4456789012345678', 'Testy McTestFace', '08/21', '08/26', '345', 'expired'),
(8, 1, 'TSB', 'Mastercard', 'Debit', '5456789012345678', 'Testy McTestFace', '11/20', '11/23', '678', 'expired'),
(9, 1, 'Nationwide', 'VISA', 'Credit', '4567890123456789', 'Testy McTestFace', '04/22', '04/27', '901', 'expired'),
(10, 1, 'Metro Bank', 'Mastercard', 'Debit', '5567890123456789', 'Testy McTestFace', '07/19', '07/22', '234', 'expired'),
(11, 1, 'Barclays', 'VISA', 'Credit', '4678901234567890', 'Testy McTestFace', '02/21', '02/24', '567', 'expired'),
(12, 1, 'HSBC', 'Mastercard', 'Debit', '5678901234567890', 'Testy McTestFace', '05/20', '05/23', '890', 'expired'),
(13, 1, 'NatWest', 'VISA', 'Credit', '4789012345678901', 'Testy McTestFace', '10/21', '10/26', '123', 'expired'),
(14, 1, 'Lloyds', 'Mastercard', 'Debit', '5789012345678901', 'Testy McTestFace', '01/19', '01/22', '456', 'expired'),
(15, 1, 'Santander', 'American Express', 'Credit', '3789012345678901', 'Testy McTestFace', '12/21', '12/26', '789', 'expired'),
(16, 1, 'Halifax', 'VISA', 'Debit', '4890123456789012', 'Testy McTestFace', '03/20', '03/23', '012', 'expired'),
(17, 1, 'RBS', 'Mastercard', 'Credit', '5890123456789012', 'Testy McTestFace', '06/19', '06/22', '345', 'expired'),
(18, 1, 'TSB', 'VISA', 'Debit', '4901234567890123', 'Testy McTestFace', '09/22', '09/27', '678', 'valid'),
(19, 1, 'Nationwide', 'Mastercard', 'Credit', '5901234567890123', 'Testy McTestFace', '11/21', '11/26', '901', 'valid'),
(20, 1, 'Metro Bank', 'American Express', 'Credit', '3901234567890123', 'Testy McTestFace', '08/22', '08/27', '234', 'expired');