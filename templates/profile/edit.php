<?php

use Forms\Helpers\Session;
use Forms\Helpers\Token;
?>

<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-8 mb-3">
            <div class="card border-primary my-3">
                <h2 class="card-header text-center text-white bg-primary border-primary">Редактировать профиль</h2>

                <div class="card-body">
                    <div class="text-success text-center fs-5"><?= Session::popMessage('profile') ?? '' ?></div>

                    <form method="POST" action="/profile/edit">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="token" value="<?= htmlspecialchars(Token::get()) ?>">
                        <div class="row text-center">
                            <strong class="text-danger"><?= $errors['token'] ?? '' ?></strong>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="col-md-8">
                                <label for="name" class="col-md-6 col-form-label">Имя:</label>
                                <input id="name" type="name" class="form-control border-secondary" name="name" value="<?= htmlspecialchars($user->getName()) ?>" autocomplete="name" autofocus>
                                <strong class="text-danger"><?= $errors['name'] ?? '' ?></strong>
                            </div>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="col-md-8">
                                <label for="phone" class="col-md-6 col-form-label">Телефон:</label>
                                <input id="phone" type="tel" class="form-control border-secondary" name="phone" value="<?= htmlspecialchars($user->getPhone()) ?>" autocomplete="phone">
                                <small>Без +7, 8, скобок, дефисов и пробелов</small>
                                <div><strong class="text-danger"><?= $errors['phone'] ?? '' ?></strong></div>
                            </div>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="col-md-8">
                                <label for="email" class="col-md-6 col-form-label">Email:</label>
                                <input id="email" type="email" class="form-control border-secondary" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" autocomplete="email">
                                <strong class="text-danger"><?= $errors['email'] ?? '' ?></strong>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-outline-primary">Сохранить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-3">
            <div class="card border-primary my-3">
                <h2 class="card-header text-center text-white bg-primary border-primary">Сменить пароль</h2>

                <div class="card-body">
                    <div class="text-success text-center fs-5"><?= Session::popMessage('password') ?? '' ?></div>

                    <form method="POST" action="/profile/password/edit">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="token" value="<?= htmlspecialchars(Token::get()) ?>">
                        <div class="row text-center">
                            <strong class="text-danger"><?= $errors['token'] ?? '' ?></strong>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="col-md-8">
                                <label for="current_password" class="col-md-6 col-form-label">Текущий пароль:</label>
                                <input id="current_password" type="password" class="form-control border-secondary" name="current_password" required>
                                <strong class="text-danger"><?= $errors['currentPassword'] ?? '' ?></strong>
                            </div>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="col-md-8">
                                <label for="password" class="col-md-6 col-form-label">Новый пароль:</label>
                                <input id="password" type="password" class="form-control border-secondary" name="password" required>
                                <strong class="text-danger"><?= $errors['newPassword'] ?? '' ?></strong>
                            </div>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="col-md-8">
                                <label for="confirm_password" class="col-md-6 col-form-label">Повторите новый пароль:</label>
                                <input id="confirm_password" type="password" class="form-control border-secondary" name="confirm_password" required>
                                <strong class="text-danger"><?= $errors['confirmPassword'] ?? '' ?></strong>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-outline-primary">Сохранить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-3">
            <div class="card border-danger">
                <h2 class="card-header text-center text-white bg-danger border-danger">Удалить профиль</h2>

                <div class="card-body">
                    <form method="POST" action="/profile/delete">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="token" value="<?= htmlspecialchars(Token::get()) ?>">
                        <div class="row text-center">
                            <strong class="text-danger"><?= $errors['token'] ?? '' ?></strong>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="col-md-8">
                                <label for="password" class="col-md-6 col-form-label">Пароль:</label>
                                <input id="password" type="password" class="form-control border-secondary" name="password" required>
                                <strong class="text-danger"><?= $errors['password'] ?? '' ?></strong>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-danger">Удалить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
