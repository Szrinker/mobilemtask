document.addEventListener('DOMContentLoaded', function () {
    console.log('new advertisement script loaded');
    const container = document.getElementById('images_container');
    const addButton = document.getElementById('add_image');
    let index = container.querySelectorAll('input[type=file]').length;

    addButton.addEventListener('click', function () {
        const prototype = container.dataset.prototype || container.querySelector('div[data-prototype]').dataset.prototype;
        if (!prototype) return;

        const newForm = prototype.replace(/__name__/g, index);
        const div = document.createElement('div');
        console.log(newForm, div);
        div.innerHTML = newForm;
        container.appendChild(div);

        if (index == 4) {
            addButton.remove();
            return;
        }

        index++;
    });
});