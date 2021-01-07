
# Focus Todo API

### Description

- Supports creation sub-tasks, task-todo conversion, labels.
- Flexible API

### Requirements

- PHP 8.0+
- Postgres v11+
- Postman

### Documentation
- Demo URL - [https://focus-todo-app.herokuapp.com/](https://focus-todo-app.herokuapp.com/)
- Documentation [https://documenter.getpostman.com/view/3746647/TVzNJf29](https://documenter.getpostman.com/view/3746647/TVzNJf29)

### Installation

Clone repo
```
git clone https://github.com/chikeozulumba/focus-todo
```
Install Dependencies
```
composer install
```
Setup application configuration
```
cp .env.example .env
```
Run migration
```
php artisan migrate
```
Run development server
```
php artisan serve
```
Open [http://localhost:8000](http://localhost:8000) in postman
