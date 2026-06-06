<?php
namespace Core;

class View {
    public static function render(string $view, array $data = []): void {
        $base     = dirname(__DIR__) . '/views/';
        $viewFile = $base . str_replace('/', DIRECTORY_SEPARATOR, $view) . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "View not found: $view";
            return;
        }

        extract($data, EXTR_SKIP);

        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // Determine layout
        $area       = strstr($view, '/', true);
        $layoutName = $data['layout'] ?? 'app';
        $layoutFile = $base . $area . '/layouts/' . $layoutName . '.php';

        if (file_exists($layoutFile)) {
            extract($data, EXTR_SKIP);
            include $layoutFile;
        } else {
            echo $content;
        }
    }

    public static function partial(string $partial, array $data = []): void {
        $file = dirname(__DIR__) . '/views/' . str_replace('/', DIRECTORY_SEPARATOR, $partial) . '.php';
        if (file_exists($file)) {
            extract($data, EXTR_SKIP);
            include $file;
        }
    }
}
