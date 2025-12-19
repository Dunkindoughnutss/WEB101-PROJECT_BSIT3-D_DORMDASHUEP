// (create) Show toast if URL contains created=1
function getQueryParam(name) {
    const params = new URLSearchParams(window.location.search);
    return params.get(name);
}

document.addEventListener('DOMContentLoaded', function () {
    if (getQueryParam('created') === '1') {
        const toast = document.getElementById('toast');
        toast.style.display = 'block';
        // Auto-hide after 3 seconds
        setTimeout(() => {
            toast.style.display = 'none';
            // remove the query param from the URL without reloading
            const url = new URL(window.location);
            url.searchParams.delete('created');
            window.history.replaceState({}, '', url);
        }, 3000);
    }
});


// (Login) Show toast if URL contains created=1
document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    if (params.get('created') === '1') {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
                params.delete('created');
                const url = new URL(window.location);
                url.searchParams.delete('created');
                window.history.replaceState({}, '', url);
            }, 3000);
        }
    }
});


// Set a timeout to redirect after 3 seconds but only when we're on the loading page.
// This avoids wrong redirects when the same script is included on pages in subfolders.
if (window.location.pathname && window.location.pathname.indexOf('loading.php') !== -1) {
    setTimeout(function () {
        // use an absolute path to avoid relative path problems from subfolders
        window.location.href = '/DormDash/selection.php';
    }, 3000);
}