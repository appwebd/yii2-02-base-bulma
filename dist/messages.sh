#../yii/framework/yiic shell
#./yii message ./messages/config.php
./yii message/extract @app/messages/config.php

# Para mensajes con parámetros emplear:
# Yii::t('app', 'Path alias "{alias}" is redefined.',array('{alias}'=>$alias))
