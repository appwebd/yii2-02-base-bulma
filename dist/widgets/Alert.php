<?php
namespace app\widgets;

use Yii;
use app\models\queries\Bitacora;

/**
 * Alert widget renders a message from session flash. All flash
 * messages are displayed in the sequence they were assigned using setFlash.
 * You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Alert extends \yii\base\Widget
{

    public $icons = [
        'error' => ''
    ];
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - key: the name of the session flash variable
     * - value: the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error'   => 'notification is-danger',
        'danger'  => 'notification is-danger',
        'success' => 'notification is-success',
        'info'    => 'notification is-info',
        'warning' => 'notification is-warning'
    ];
    /**
     * @var array the options for rendering the close button tag.
     * Array will be passed to [[\yii\bootstrap\Alert::closeButton]].
     */
    public $closeButton = [];

/*
 *
 * <article class="message is-danger">
  <div class="message-header">
    <p>Danger</p>
    <button class="delete" aria-label="delete"></button>
  </div>
  <div class="message-body">
    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
<strong>Pellentesque risus mi</strong>, tempus quis placerat ut,
 porta nec nulla. Vestibulum rhoncus ac ex sit amet fringilla.
 Nullam gravida purus diam, et dictum <a>felis venenatis</a>
 efficitur. Aenean ac <em>eleifend lacus</em>, in mollis lectus.
 Donec sodales, arcu et sollicitudin porttitor, tortor urna
tempor ligula, id porttitor mi magna a neque. Donec dui urna,
 vehicula et sem eget, facilisis sodales sem.
  </div>
</article>
 */
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $flash) {
            if (!isset($this->alertTypes[$type])) {
                continue;
            }

            foreach ((array) $flash as $i => $message) {
                try {
                    echo '<div class="'. $this->alertTypes[$type].'"
                            role="alert"
                            id="alert" >
                        <button class="delete" type="button"
                            onclick="OnClickRemoveMe()"
                            ></button> &nbsp; &nbsp;',
                        $message,
                        '</div>';
                } catch(\Exception $exception){
                    $bitacora = new Bitacora();
                    $bitacora->register(
                        $exception,
                        'app\widgets\Alert::run',
                        MSG_ERROR
                    );
                }
            }
            $session->removeFlash($type);
        }
    }
}
