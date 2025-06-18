# Setup

## 📁 1. Clonar el repositorio

```bash
git clone git@github.com:PedroCM101/api.git
cd api
```

## ⚙️ 2. Copiar el archivo de entorno

```bash
cp .env.example .env
```

## 📦 3. Instalar dependencias

```bash
composer install
```

## 🔑 4. Generar clave de aplicación

```bash
php artisan key:generate
```

## 🗃️ 5. Migrar y poblar la base de datos

```bash
php artisan migrate --seed
```

Esto creará las tablas y añadirá usuarios de prueba, incluyendo el usuario administrador:
admin@admin.com / 12345

## 🚀 6. Levantar el servidor

```bash
php artisan serve
```

La api estará disponible en: http://localhost:8000

## 🛠️ Bonus: tests

```bash
php artisan test
```
