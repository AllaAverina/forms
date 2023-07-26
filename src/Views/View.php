<?php

namespace Forms\Views;

use Forms\Helpers\Session;

class View
{
    private static $templatesPath = __DIR__ . '/../../templates/';

    public static function render(string $templateName, array $vars = []): void
    {
        $template = self::$templatesPath . $templateName . '.php';
        $layout = self::$templatesPath . 'layouts/default.php';

        extract($vars);

        $errors = Session::popErrors() ?? [];
        $values = Session::popInputValues() ?? [];

        ob_start();
        include $template;
        $content = ob_get_clean();
        include $layout;

        exit;
    }

    public static function renderError(int $code): void
    {
        http_response_code($code);

        $title = "Ошибка $code";
        $template = self::$templatesPath . 'errors/' . $code . '.php';
        $layout = self::$templatesPath . 'layouts/default.php';

        ob_start();
        include $template;
        $content = ob_get_clean();
        include $layout;
        exit;
    }
}
