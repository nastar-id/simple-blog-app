// Show and close 'add article' modal box
const addBtn = document.getElementById('add-artikel');
if(addBtn) {
    const closeModal = document.getElementById('close-form');
    const modalBox = document.getElementById('add-form');
    const form = document.querySelector('#add-form .modal-content-wrapper');

    addBtn.addEventListener('click', () => {
        modalBox.style.opacity = '1';
        modalBox.style.zIndex = '150';

        setTimeout(() => {
            form.style.transform = 'scale(1)';
        }, 300);
    });

    closeModal.addEventListener('click', () => {
        form.removeAttribute('style');

        setTimeout(() => {
            modalBox.removeAttribute('style');
        }, 400);
    });
}

const hamburger = document.getElementById('toggle');
if(hamburger) {
    hamburger.addEventListener('click', () => {
        const sidenav = document.querySelector('.navigation .nav-links');
        if(hamburger.checked) {
            sidenav.style.transform = 'translateX(0)';
        } else {
            sidenav.removeAttribute('style');
        }
    });
}

// const src = document.getElementById('src');
// if(src) {
//     const form = document.querySelector('.search-bar form');
//     form.addEventListener('submit', () => {
//         const searchVal = document.querySelector('input[name="src"]');
//         window.location = 'index.php?src=' + searchVal.value;
//     });
// }