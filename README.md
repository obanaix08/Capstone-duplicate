# Unick Enterprises OPS - Order, Inventory, Production System

This repository contains a Laravel API backend and a React + Bootstrap frontend for a modern order processing, inventory and production tracking system.

## Quick Start

Backend
1. Copy env and generate key
   - `cp backend/.env.example backend/.env`
   - `cd backend && php artisan key:generate`
2. Use sqlite for local dev or configure MySQL in `.env`. Then run:
   - `php artisan migrate --force`
   - `php artisan db:seed --force`

Frontend
1. `cd frontend && npm i`
2. `npm run dev` (Vite dev server)

Auth
- Default admin: email `admin@unick.local`, password `password123`

## API Overview
- Auth: `/api/auth/login`, `/api/auth/register`, `/api/auth/logout`
- Dashboard: `/api/dashboard/overview`, `/api/dashboard/charts`
- Inventory: `/api/inventory`, `/api/inventory/low-stock`
- Productions: `/api/productions`
- Orders: `/api/orders` and `POST /api/orders/{id}/status`
- Reports: `/api/reports/*`, export via `/api/reports/export?type=sales&format=pdf|csv|xlsx`
- Forecasting: `/api/forecasting/overview`
- Customers: `/api/customers`

## Realtime
Configure Pusher credentials in `backend/.env` to enable live notifications for new orders and low stock.