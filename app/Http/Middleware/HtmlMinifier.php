<?php

namespace App\Http\Middleware;

use Closure;

class HtmlMinifier
{
    public function handle($request, Closure $next)
    {
        // Proses request ke controller
        $response = $next($request);

        // Periksa jika response berupa HTML
        $contentType = $response->headers->get('Content-Type');
        if (strpos($contentType, 'text/html') !== false) {
            $buffer = $response->getContent();
        
            // Pengecualian untuk script
            if (preg_match_all('/<script\b[^>]*>(.*?)<\/script>/is', $buffer, $matches)) {
                $scripts = $matches[0];
                foreach ($scripts as $script) {
                    $placeholder = '###SCRIPT_' . md5($script) . '###';
                    $buffer = str_replace($script, $placeholder, $buffer);
                    $placeholders[$placeholder] = $script;
                }
            }
        
            $replace = [
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/\n([\S])/" => '$1',
                "/\r/" => '',
                "/\n/" => '',
                "/\t/" => '',
                "/ +/" => ' ',
            ];
            $buffer = preg_replace(array_keys($replace), array_values($replace), $buffer);
        
            // Kembalikan script ke tempatnya
            if (!empty($placeholders)) {
                foreach ($placeholders as $placeholder => $script) {
                    $buffer = str_replace($placeholder, $script, $buffer);
                }
            }
        
            $response->setContent($buffer);
        }
        

        return $response;
    }
}
