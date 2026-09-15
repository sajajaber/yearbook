<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StyleMediaFeedback
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->routeIs('media.index') || ! str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            return $response;
        }

        $feedback = <<<'HTML'
<style id="media-feedback-styles">
.media-feedback-stack{position:fixed;top:84px;right:24px;z-index:2000;width:min(440px,calc(100vw - 32px));display:grid;gap:12px;pointer-events:none}
.media-feedback{position:relative;display:grid;grid-template-columns:42px minmax(0,1fr) 30px;align-items:start;gap:12px;padding:15px 14px 15px 15px;border:1px solid;border-radius:16px;background:#fff;box-shadow:0 18px 45px rgba(0,42,92,.16);pointer-events:auto;animation:mediaFeedbackIn .35s cubic-bezier(.16,1,.3,1);overflow:hidden}
.media-feedback::before{content:"";position:absolute;inset:0 auto 0 0;width:4px}
.media-feedback-error{border-color:#f1c5c2;background:#fffafa}
.media-feedback-error::before{background:#c0392b}
.media-feedback-success{border-color:#b9dfcc;background:#fbfffc}
.media-feedback-success::before{background:#2f855a}
.media-feedback-icon{width:42px;height:42px;display:grid;place-items:center;border-radius:12px;font-size:20px;font-weight:900}
.media-feedback-error .media-feedback-icon{background:#fce8e6;color:#b42318}
.media-feedback-success .media-feedback-icon{background:#e4f6eb;color:#24734b}
.media-feedback-copy{min-width:0;padding-top:1px}
.media-feedback-label{margin:0 0 3px;color:#002a5c;font-size:.68rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase}
.media-feedback-message{margin:0;color:#526477;font-size:.84rem;line-height:1.55;overflow-wrap:anywhere}
.media-feedback-close{display:grid;place-items:center;width:30px;height:30px;border:0;border-radius:9px;background:transparent;color:#64748b;font-size:19px;line-height:1;cursor:pointer;transition:.15s ease}
.media-feedback-close:hover{background:#edf3f8;color:#002a5c}
.media-feedback-progress{position:absolute;left:0;bottom:0;height:2px;width:100%;transform-origin:left;animation:mediaFeedbackProgress 7s linear forwards}
.media-feedback-error .media-feedback-progress{background:#c0392b}
.media-feedback-success .media-feedback-progress{background:#2f855a}
@keyframes mediaFeedbackIn{from{opacity:0;transform:translateY(-10px) scale(.98)}to{opacity:1;transform:none}}
@keyframes mediaFeedbackProgress{from{transform:scaleX(1)}to{transform:scaleX(0)}}
@media(max-width:600px){.media-feedback-stack{top:70px;right:16px;width:calc(100vw - 32px)}.media-feedback{grid-template-columns:36px minmax(0,1fr) 28px;padding:13px 11px 13px 13px}.media-feedback-icon{width:36px;height:36px;border-radius:10px}}
@media(prefers-reduced-motion:reduce){.media-feedback{animation:none}.media-feedback-progress{animation:none}}
</style>
<script>
document.addEventListener('DOMContentLoaded',function(){
    document.querySelectorAll('.media-feedback').forEach(function(alert){
        var close=alert.querySelector('.media-feedback-close');
        var timer=window.setTimeout(function(){alert.remove();},7000);
        if(close){close.addEventListener('click',function(){window.clearTimeout(timer);alert.remove();});}
    });
});
</script>
HTML;

        $body = $response->getContent();
        $body = str_replace('</head>', $feedback . '</head>', $body);
        $body = preg_replace_callback(
            '/<div class="alert alert-(success|error)"([^>]*)>(.*?)<\/div>/s',
            function (array $matches): string {
                $type = $matches[1];
                $label = $type === 'error' ? 'Upload not completed' : 'Upload complete';
                $icon = $type === 'error' ? '!' : '✓';
                return '<div class="media-feedback-stack"><div class="media-feedback media-feedback-' . $type . '" role="' . ($type === 'error' ? 'alert' : 'status') . '">' .
                    '<div class="media-feedback-icon" aria-hidden="true">' . $icon . '</div>' .
                    '<div class="media-feedback-copy"><p class="media-feedback-label">' . $label . '</p><p class="media-feedback-message">' . $matches[3] . '</p></div>' .
                    '<button type="button" class="media-feedback-close" aria-label="Dismiss message">&times;</button>' .
                    '<span class="media-feedback-progress" aria-hidden="true"></span>' .
                    '</div></div>';
            },
            $body
        );

        $response->setContent($body);

        return $response;
    }
}
