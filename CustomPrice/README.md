Add custom price attribute
Описание
    • Добавить новый атрибут товара custom_price типа price.
    • В админке этот атрибут должен выводится вместе с чекбоксом Allow Modify, чекбокс по умолчанию не выбран.
    • Если чекбокс не выбран, то поле атрибута не активно (нельзя редактировать), и при сохранении товара custom_price должен автоматически пересчитываться и быть на 15% больше цены товара.
    • Если чекбокс выбран, то поле атрибута активно (можно редактировать), и сохраняется введенное из админ панели значение.
    • На сторфронте на странице категории изменить вывод цены, что бы выводилась custom_price.
    • Убедится, что на странице товара вывод цены не изменился.
    • Добавить фильтр по custom_price и убрать фильтр по обычной цене (это делается конфигурацией в админ панели)
    • В админ панели в разделе конфигураций добавить поле для ввода % на который должен быть больше custom_price, по умолчанию 15%.
    • Доработать сохранение товара, с учетом новой настройки.
