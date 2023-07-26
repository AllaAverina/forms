<?php

use Forms\Helpers\Token;
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-primary my-3">
                <h2 class="card-header text-center text-white bg-primary border-primary">Вход</h2>

                <div class="card-body">
                    <form method="POST" action="/login">
                        <input type="hidden" name="token" value="<?= htmlspecialchars(Token::get()) ?>">
                        <div class="row text-center">
                            <strong class="text-danger"><?= $errors['token'] ?? '' ?></strong>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="col-md-8">
                                <label for="login" class="col-md-4 col-form-label">Телефон или Email:</label>
                                <input id="login" type="text" class="form-control border-secondary" name="login" value="<?= htmlspecialchars($values['name'] ?? '') ?>" required autocomplete="login" autofocus>
                                <small>Телефон вводите без +7, 8, скобок, дефисов и пробелов</small>
                                <div><strong class="text-danger"><?= $errors['login'] ?? '' ?></strong></div>
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
                                <div class="g-recaptcha" data-sitekey="6Lc-KFYnAAAAAKFS8JkxVa_hIoagagHSTTp6oC6_"></div>
                                <strong class="text-danger"><?= $errors['recaptcha'] ?? '' ?></strong>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-outline-primary">Войти</button>
                                <a class="btn-link" href="/register">Зарегистрироваться</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
