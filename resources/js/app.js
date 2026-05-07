import './bootstrap';
import 'bootstrap';
import $ from 'jquery';
import Swal from 'sweetalert2';

window.$ = $;
window.jQuery = $;
window.Swal = Swal;

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

document.addEventListener('DOMContentLoaded', () => {
	showFlashMessages();
	wireCopyButtons();
	wireConfirmForms();
	wireAjaxForms();
	revealOnScroll();
	wirePasswordToggles();
});