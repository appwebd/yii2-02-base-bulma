# yii2-base-bulma
Base project in framework yii2 using framework css Bulma (in development) 

This project is intended to write code, so that it can be so independent of the user interface. 
For this, in each project yii2-base, yii2-base-bulma, yii2-base-bootstrap4 have been written in 
```
app\components\Buttons.php
app\components\UiComponents.php
```
classes that are dependent on the framework used in the user interface (bootstrap 3.3.9, bootstrap4 or bulma).

there are some views that have mixed code, the usual coding of some views 
(PHP with any framework CSS), like other table maintainers where the classes
 that make the user interface abstraction have been used
