import './bootstrap';
import * as bootstrap from 'bootstrap';
import $ from 'jquery';
import Swal from 'sweetalert2';

window.$ = $;
window.jQuery = $;
window.Swal = Swal;
window.bootstrap = bootstrap;

const toast = Swal.mixin({
	toast: true,
	position: 'top-end',
	showConfirmButton: false,
	timer: 2600,
	timerProgressBar: true,
});

function showFlashMessages() {
	const flash = {
		success: document.body?.dataset.flashSuccess || '',
		error: document.body?.dataset.flashError || '',
	};

	if (flash.success) {
		toast.fire({ icon: 'success', title: flash.success });
	}

	if (flash.error) {
		toast.fire({ icon: 'error', title: flash.error });
	}
}

function wireCopyButtons() {
	document.querySelectorAll('[data-copy-target]').forEach((button) => {
		button.addEventListener('click', async () => {
			const target = document.querySelector(button.dataset.copyTarget);
			const value = target?.value || target?.textContent || '';

			if (!value) {
				toast.fire({ icon: 'warning', title: 'Nothing to copy' });
				return;
			}

			await navigator.clipboard.writeText(value.trim());
			toast.fire({ icon: 'success', title: button.dataset.copyMessage || 'Copied successfully' });
		});
	});
}

function wireConfirmForms() {
	document.addEventListener('submit', (event) => {
		const form = event.target;
		if (!(form instanceof HTMLFormElement) || !form.matches('[data-confirm]')) {
			return;
		}

		event.preventDefault();

		Swal.fire({
			icon: form.dataset.confirmIcon || 'warning',
			title: form.dataset.confirmTitle || 'Are you sure?',
			text: form.dataset.confirmText || 'This action will be applied immediately.',
			confirmButtonText: form.dataset.confirmButton || 'Yes, continue',
			cancelButtonText: 'Cancel',
			showCancelButton: true,
			confirmButtonColor: '#c96d26',
			cancelButtonColor: '#6b7280',
		}).then((result) => {
			if (result.isConfirmed) {
				form.submit();
			}
		});
	});
}

function wireAjaxForms() {
	document.addEventListener('submit', async (event) => {
		const form = event.target;
		if (!(form instanceof HTMLFormElement) || !form.matches('.js-ajax-form')) {
			return;
		}

		event.preventDefault();

		const submitButton = form.querySelector('[type="submit"]');
		const originalText = submitButton?.innerHTML;
		const formData = new FormData(form);

		if (submitButton) {
			submitButton.disabled = true;
			submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing';
		}

		try {
			const response = await window.axios.post(form.action, formData, {
				headers: { Accept: 'application/json' },
			});

			const payload = response.data || {};
			if (payload.message) {
				toast.fire({ icon: payload.status || 'success', title: payload.message });
			}

			if (payload.removeRow && payload.rowId) {
				document.getElementById(payload.rowId)?.remove();
			}

			if (payload.reload) {
				window.location.reload();
			}
		} catch (error) {
			const message = error.response?.data?.message || 'Request failed. Please try again.';
			toast.fire({ icon: 'error', title: message });
		} finally {
			if (submitButton) {
				submitButton.disabled = false;
				submitButton.innerHTML = originalText;
			}
		}
	});
}

function revealOnScroll() {
	const items = document.querySelectorAll('[data-reveal]');
	if (!items.length) {
		return;
	}

	const observer = new IntersectionObserver((entries) => {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				entry.target.classList.add('is-visible');
				observer.unobserve(entry.target);
			}
		});
	}, { threshold: 0.12 });

	items.forEach((item) => observer.observe(item));
}

function wirePasswordToggles() {
	document.querySelectorAll('[data-toggle-password]').forEach((button) => {
		button.addEventListener('click', () => {
			const targetSelector = button.getAttribute('data-toggle-password');
			const input = targetSelector ? document.querySelector(targetSelector) : null;
			if (!(input instanceof HTMLInputElement)) {
				return;
			}

			const isPassword = input.type === 'password';
			input.type = isPassword ? 'text' : 'password';
			const icon = button.querySelector('i');
			if (icon) {
				icon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
			}
		});
	});
}

function wireMobileMenu() {
	const offcanvas = document.getElementById('mobileMenu');
	if (!offcanvas || typeof window.bootstrap?.Offcanvas !== 'function') return;
	const instance = window.bootstrap.Offcanvas.getOrCreateInstance(offcanvas);

	document.querySelectorAll('#mobileMenu .mobile-nav-link').forEach((link) => {
		link.addEventListener('click', () => {
			if (link.getAttribute('target') === '_blank') {
				instance.hide();
				return;
			}
			instance.hide();
		});
	});
}

function wireMobileSidebar() {
	const toggles = document.querySelectorAll('[data-mobile-sidebar-toggle]');
	const sidebar = document.querySelector('#sidebar');
	if (!toggles.length || !sidebar) return;

	if (document.body.dataset.mobileSidebarWired === '1') return;
	document.body.dataset.mobileSidebarWired = '1';

	const closeSidebar = () => {
		sidebar.classList.remove('open');
		document.body.classList.remove('mobile-sidebar-open');
		document.documentElement.classList.remove('mobile-sidebar-open');
		const backdrop = document.getElementById('mobile-sidebar-backdrop');
		if (backdrop) {
			backdrop.classList.remove('show');
			backdrop.style.display = 'none';
		}
	};

	const openSidebar = () => {
		sidebar.classList.add('open');
		document.body.classList.add('mobile-sidebar-open');
		document.documentElement.classList.add('mobile-sidebar-open');
		let backdrop = document.getElementById('mobile-sidebar-backdrop');
		if (!backdrop) {
			backdrop = document.createElement('div');
			backdrop.id = 'mobile-sidebar-backdrop';
			backdrop.className = 'mobile-sidebar-backdrop';
			document.body.appendChild(backdrop);
		}
		backdrop.classList.add('show');
		backdrop.style.display = 'block';
	};

	const toggleSidebar = (event) => {
		event?.preventDefault();
		event?.stopPropagation();
		if (sidebar.classList.contains('open')) {
			closeSidebar();
		} else {
			openSidebar();
		}
	};

	toggles.forEach((toggle) => {
		toggle.addEventListener('click', (event) => {
			const targetSelector = toggle.getAttribute('data-mobile-sidebar-target') || '#sidebar';
			const targetSidebar = document.querySelector(targetSelector);
			if (!targetSidebar) return;
			toggleSidebar(event);
		});
	});

	sidebar.addEventListener('click', (event) => {
		event.stopPropagation();
	});

	document.addEventListener('click', (event) => {
		if (event.target.id === 'mobile-sidebar-backdrop') {
			closeSidebar();
		}
	});

	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && sidebar.classList.contains('open')) {
			closeSidebar();
		}
	});

	window.addEventListener('resize', () => {
		if (window.innerWidth >= 768) {
			closeSidebar();
		}
	});
}

// Run each initializer in isolation so one failing function
// can never block the rest (e.g. sidebars) from wiring up.
function safe(fn, label) {
	try {
		fn();
	} catch (error) {
		console.error(`[app.js] "${label}" failed:`, error);
	}
}

document.addEventListener('DOMContentLoaded', () => {
	safe(showFlashMessages, 'showFlashMessages');
	safe(wireCopyButtons, 'wireCopyButtons');
	safe(wireConfirmForms, 'wireConfirmForms');
	safe(wireAjaxForms, 'wireAjaxForms');
	safe(revealOnScroll, 'revealOnScroll');
	safe(wirePasswordToggles, 'wirePasswordToggles');
	safe(wireMobileMenu, 'wireMobileMenu');
	safe(wireMobileSidebar, 'wireMobileSidebar');
});