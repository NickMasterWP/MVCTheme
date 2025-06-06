document.addEventListener('DOMContentLoaded', function() {
    // Обработка добавления нового элемента
    document.querySelectorAll('.add-repeater-item').forEach(function(button) {
        button.addEventListener('click', function() {
            var repeaterContainer = button.closest('.form__field_repeater');
            var repeaterItemTemplate = repeaterContainer.querySelector('.repeater-item-template');
            var table = repeaterContainer.querySelector('.repeater-items');

            // Клонируем шаблон
            var newRow = repeaterItemTemplate.cloneNode(true);

            // Убираем класс шаблона и делаем элемент видимым
            newRow.classList.remove('repeater-item-template');
            newRow.style.display = '';

            // Генерируем уникальный индекс для нового элемента
            var index = Date.now(); // Используем временную метку как уникальный индекс

            // Заменяем __index__ на реальный индекс в именах полей
            newRow.innerHTML = newRow.innerHTML.replace(/__index__/g, index);

            // Вставляем новую строку в таблицу
            table.appendChild(newRow);
        });
    });

    // Обработка удаления элемента (делегирование событий)
    document.addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('remove-repeater-item')) {
            var row = event.target.closest('tr');
            if (row) {
                row.remove(); // Удаляем строку таблицы
            }
        }
    });
});