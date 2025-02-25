# Amazon Server (amazon_svr)

## Project Overview

`amazon_svr` is a backend server application for an Amazon-like e-commerce platform. It is built using PHP and connects to a MySQL database. The application provides RESTful API endpoints for various e-commerce functionalities including user management, shopping basket operations, order processing, and address management.

## Purpose

This project is part of my portfolio to demonstrate my capabilities in backend development, database management, and RESTful API design. It showcases my understanding of modern web development practices, design patterns, and security measures.

## Features

- **User Management**
  - User registration and authentication
  - Password hashing and secure token management
  - Profile management

- **Shopping Basket Operations**
  - Add, update, and remove items in the basket
  - Sync guest baskets

- **Order Processing**
  - Create and manage orders
  - Transaction management for order consistency

- **Address Management**
  - CRUD operations for user addresses
  - Default and billing address handling

- **Security**
  - Prepared statements for SQL queries
  - Token-based authentication
  - Error handling and logging

## Technologies Used

- **Backend**: PHP
- **Database**: MySQL
- **ORM**: PDO (PHP Data Objects)
- **Environment Management**: Dotenv
- **Authentication**: Token-based (with refresh tokens)
- **Design Patterns**: Singleton, Repository, Middleware
