<?php

use Forms\Helpers\Token;
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 my-3">
            <div class="card border-primary">
                <h2 class="card-header text-center text-white bg-primary border-primary"><?= htmlspecialchars($user->getName()) ?></h2>

                <div class="card-body text-center my-2">
                    <div>Телефон: <?= htmlspecialchars($user->getPhone()) ?></div>
                    <div>Email: <?= htmlspecialchars($user->getEmail()) ?></div>
                </div>

                <div class="card-footer border-primary bg-transparent text-center">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <a class="btn btn-outline-primary" href="/profile/edit">Редактировать</a>
                        </div>

                        <div class="col-md-6">
                            <form method="POST" action="/logout">
                                <input type="hidden" name="token" value="<?= htmlspecialchars(Token::get()) ?>">
                                <div class="row text-center">
                                    <strong class="text-danger"><?= $errors['token'] ?? '' ?></strong>
                                </div>

                                <button type="submit" class="btn btn-outline-danger">Выйти</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
