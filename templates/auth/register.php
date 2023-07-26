<?php

use Forms\Helpers\Token;
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-primary my-3">
                <h2 class="card-header text-center text-white bg-primary border-primary">Регистрация</h2>

                <div class="card-body">
                    <form method="POST" action="/register">
                        <input type="hidden" name="token" value="<?= htmlspecialchars(Token::get()) ?>">
                        <div class="row text-center">
                            <strong class="text-danger"><?= $errors['token'] ?? '' ?></strong>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="col-md-8">
                                <label for="name" class="col-md-4 col-form-label">Имя:</label>
                                <input id="name" type="name" class="form-control border-secondary" name="name" value="<?= htmlspecialchars($values['name'] ?? '') ?>" required autocomplete="name" autofocus>
                                <strong class="text-danger"><?= $errors['name'] ?? '' ?></strong>
                            </div>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="col-md-8">
                                <label for="phone" class="col-md-4 col-form-label">Телефон:</label>
                                <input id="phone" type="tel" class="form-control border-secondary" name="phone" value="<?= htmlspecialchars($values['phone'] ?? '') ?>" required autocomplete="phone">
                                <small>Без +7, 8, скобок, дефисов и пробелов</small>
                                <div><strong class="text-danger"><?= $errors['phone'] ?? '' ?></strong></div>
                            </div>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="col-md-8">
                                <label for="email" class="col-md-4 col-form-label">Email:</label>
                                <input id="email" type="email" class="form-control border-secondary" name="email" value="<?= htmlspecialchars($values['email'] ?? '') ?>" required autocomplete="email">
                                <strong class="text-danger"><?= $errors['email'] ?? '' ?></strong>
                            </div>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="col-md-8">
                                <label for="password" class="col-md-4 col-form-label">Пароль:</label>
                                <input id="password" type="password" class="form-control border-secondary" name="password" required>
                                <strong class="text-danger"><?= $errors['password'] ?? '' ?></strong>
                            </div>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="col-md-8">
                                <label for="confirm_password" class="col-md-4 col-form-label">Повторите пароль:</label>
                                <input id="confirm_password" type="password" class="form-control border-secondary" name="confirm_password" required>
                                <strong class="text-danger"><?= $errors['confirmPassword'] ?? '' ?></strong>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-outline-primary">Зарегистрироваться</button>
                                <a class="btn-link" href="/login">Войти</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
