    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            function confirmLogout() {
                Swal.fire({
                    title: 'Apakah Anda yakin ingin logout?',
                    text: 'Anda akan keluar dari sesi ini.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Logout!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to the logout page or trigger your logout logic here
                        window.location.href = '../systems/logout.php';
                    }
                });
            }
            // start: Sidebar
            const sidebarToggle = document.querySelector('.sidebar-toggle')
            const sidebarOverlay = document.querySelector('.sidebar-overlay')
            const sidebarMenu = document.querySelector('.sidebar-menu')
            const main = document.querySelector('.main')
            sidebarToggle.addEventListener('click', function (e) {
                e.preventDefault()
                main.classList.toggle('active')
                sidebarOverlay.classList.toggle('hidden')
                sidebarMenu.classList.toggle('-translate-x-full')
            })
            sidebarOverlay.addEventListener('click', function (e) {
                e.preventDefault()
                main.classList.add('active')
                sidebarOverlay.classList.add('hidden')
                sidebarMenu.classList.add('-translate-x-full')
            })
            document.querySelectorAll('.sidebar-dropdown-toggle').forEach(function (item) {
                item.addEventListener('click', function (e) {
                    e.preventDefault()
                    const parent = item.closest('.group')
                    if (parent.classList.contains('selected')) {
                        parent.classList.remove('selected')
                    } else {
                        document.querySelectorAll('.sidebar-dropdown-toggle').forEach(function (i) {
                            i.closest('.group').classList.remove('selected')
                        })
                        parent.classList.add('selected')
                    }
                })
            })
            // end: Sidebar



            // start: Popper
            const popperInstance = {}
            document.querySelectorAll('.dropdown').forEach(function (item, index) {
                const popperId = 'popper-' + index
                const toggle = item.querySelector('.dropdown-toggle')
                const menu = item.querySelector('.dropdown-menu')
                menu.dataset.popperId = popperId
                popperInstance[popperId] = Popper.createPopper(toggle, menu, {
                    modifiers: [
                        {
                            name: 'offset',
                            options: {
                                offset: [0, 8],
                            },
                        },
                        {
                            name: 'preventOverflow',
                            options: {
                                padding: 24,
                            },
                        },
                    ],
                    placement: 'bottom-end'
                });
            })
            document.addEventListener('click', function (e) {
                const toggle = e.target.closest('.dropdown-toggle')
                const menu = e.target.closest('.dropdown-menu')
                if (toggle) {
                    const menuEl = toggle.closest('.dropdown').querySelector('.dropdown-menu')
                    const popperId = menuEl.dataset.popperId
                    if (menuEl.classList.contains('hidden')) {
                        hideDropdown()
                        menuEl.classList.remove('hidden')
                        showPopper(popperId)
                    } else {
                        menuEl.classList.add('hidden')
                        hidePopper(popperId)
                    }
                } else if (!menu) {
                    hideDropdown()
                }
            })

            function hideDropdown() {
                document.querySelectorAll('.dropdown-menu').forEach(function (item) {
                    item.classList.add('hidden')
                })
            }
            function showPopper(popperId) {
                popperInstance[popperId].setOptions(function (options) {
                    return {
                        ...options,
                        modifiers: [
                            ...options.modifiers,
                            { name: 'eventListeners', enabled: true },
                        ],
                    }
                });
                popperInstance[popperId].update();
            }
            function hidePopper(popperId) {
                popperInstance[popperId].setOptions(function (options) {
                    return {
                        ...options,
                        modifiers: [
                            ...options.modifiers,
                            { name: 'eventListeners', enabled: false },
                        ],
                    }
                });
            }
            // end: Popper



            // start: Tab
            document.querySelectorAll('[data-tab]').forEach(function (item) {
                item.addEventListener('click', function (e) {
                    e.preventDefault()
                    const tab = item.dataset.tab
                    const page = item.dataset.tabPage
                    const target = document.querySelector('[data-tab-for="' + tab + '"][data-page="' + page + '"]')
                    document.querySelectorAll('[data-tab="' + tab + '"]').forEach(function (i) {
                        i.classList.remove('active')
                    })
                    document.querySelectorAll('[data-tab-for="' + tab + '"]').forEach(function (i) {
                        i.classList.add('hidden')
                    })
                    item.classList.add('active')
                    target.classList.remove('hidden')
                })
            })
            // end: Tab
        </script>
</body>
</html>