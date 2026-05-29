// --- WhatsApp Central Configuration ---
const WHATSAPP_NUMBER = window.whatsappNumber || "6287776666680"; 
const DEFAULT_WHATSAPP_MESSAGE = window.whatsappMessage || "Halo Bufflab Clean Shoes Surabaya! Saya tertarik dengan layanan shoecare Anda dan ingin bertanya lebih lanjut.";

function getWhatsAppUrl(message) {
    return `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(message)}`;
}

// Apply dynamic links to all elements with class 'whatsapp-btn'
document.querySelectorAll('.whatsapp-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        window.open(getWhatsAppUrl(DEFAULT_WHATSAPP_MESSAGE), '_blank');
    });
});

// --- Mobile Menu Toggle ---
const toggleBtn = document.getElementById('mobile-menu-toggle');
const mobileMenu = document.getElementById('mobile-menu');
const menuIcon = document.getElementById('menu-icon');

if (toggleBtn && mobileMenu && menuIcon) {
    toggleBtn.addEventListener('click', () => {
        const isHidden = mobileMenu.classList.contains('hidden');
        if (isHidden) {
            mobileMenu.classList.remove('hidden');
            // Force a layout reflow to trigger transition
            void mobileMenu.offsetHeight;
            mobileMenu.classList.remove('scale-y-95', 'opacity-0');
            mobileMenu.classList.add('scale-y-100', 'opacity-100');
            menuIcon.textContent = 'close';
        } else {
            mobileMenu.classList.remove('scale-y-100', 'opacity-100');
            mobileMenu.classList.add('scale-y-95', 'opacity-0');
            menuIcon.textContent = 'menu';
            setTimeout(() => {
                if (!mobileMenu.classList.contains('scale-y-100')) {
                    mobileMenu.classList.add('hidden');
                }
            }, 300);
        }
    });

    // Close mobile menu on clicking any navigation link
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.remove('scale-y-100', 'opacity-100');
            mobileMenu.classList.add('scale-y-95', 'opacity-0');
            menuIcon.textContent = 'menu';
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 300);
        });
    });
}

// --- Header Scroll Styling ---
const header = document.querySelector('header');
if (header) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            header.classList.add('shadow-md', 'bg-surface-container-lowest/95');
            header.classList.remove('shadow-sm', 'bg-surface-container-lowest/90');
        } else {
            header.classList.remove('shadow-md', 'bg-surface-container-lowest/95');
            header.classList.add('shadow-sm', 'bg-surface-container-lowest/90');
        }
    });
}

// --- Intersection Observer for reveal animations ---
const revealObserverOptions = {
    threshold: 0.15,
    rootMargin: '0px 0px -50px 0px'
};

const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
        }
    });
}, revealObserverOptions);

document.querySelectorAll('.reveal').forEach(el => {
    revealObserver.observe(el);
});

// --- Magnetic-ish effect for primary buttons ---
document.querySelectorAll('.btn-interact').forEach(btn => {
    btn.addEventListener('mousemove', (e) => {
        const rect = btn.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;
        btn.style.transform = `translate(${x * 0.15}px, ${y * 0.15}px) scale(1.05)`;
    });
    btn.addEventListener('mouseleave', () => {
        btn.style.transform = '';
    });
});

// --- Smooth scroll handling for all links ---
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// --- Price Calculator Logic ---
const calcCards = document.querySelectorAll('.calc-card');
const qtyValueEl = document.getElementById('qty-value');
const qtyMinusBtn = document.getElementById('qty-minus');
const qtyPlusBtn = document.getElementById('qty-plus');
const summaryItemsEl = document.getElementById('calc-summary-items');
const summaryQtyEl = document.getElementById('calc-summary-qty');
const totalPriceEl = document.getElementById('calc-total-price');
const bookBtn = document.getElementById('calc-book-btn');

if (calcCards.length > 0 && qtyValueEl && qtyMinusBtn && qtyPlusBtn && summaryItemsEl && summaryQtyEl && totalPriceEl && bookBtn) {
    let selectedServices = new Map(); // Store: id -> {name, price}
    let quantity = 1;

    calcCards.forEach(card => {
        const checkbox = card.querySelector('.calc-checkbox');
        const checkIcon = card.querySelector('.check-icon');
        const checkboxBox = card.querySelector('.checkbox-box');
        const price = parseInt(checkbox.value, 10);
        const name = checkbox.getAttribute('data-name');
        const id = checkbox.id;

        card.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent double triggering
            
            const isChecked = !checkbox.checked;
            checkbox.checked = isChecked;

            if (isChecked) {
                card.classList.add('border-secondary', 'bg-secondary/5');
                card.classList.remove('border-transparent', 'bg-surface-container-lowest');
                checkboxBox.classList.add('bg-secondary', 'border-secondary');
                checkIcon.classList.remove('hidden');
                selectedServices.set(id, { name, price });
            } else {
                card.classList.remove('border-secondary', 'bg-secondary/5');
                card.classList.add('border-transparent', 'bg-surface-container-lowest');
                checkboxBox.classList.remove('bg-secondary', 'border-secondary');
                checkIcon.classList.add('hidden');
                selectedServices.delete(id);
            }
            updateCalculator();
        });
    });

    qtyMinusBtn.addEventListener('click', () => {
        if (quantity > 1) {
            quantity--;
            qtyValueEl.textContent = quantity;
            updateCalculator();
        }
    });

    qtyPlusBtn.addEventListener('click', () => {
        quantity++;
        qtyValueEl.textContent = quantity;
        updateCalculator();
    });

    function formatIDR(amount) {
        return 'Rp ' + amount.toLocaleString('id-ID');
    }

    function updateCalculator() {
        let servicesTotal = 0;
        summaryItemsEl.innerHTML = '';

        if (selectedServices.size === 0) {
            summaryItemsEl.innerHTML = '<p class="text-on-surface-variant text-sm italic py-md text-center">Belum ada layanan terpilih</p>';
            summaryQtyEl.textContent = '1 pasang sepatu';
            totalPriceEl.textContent = 'Rp 0';
            return;
        }

        selectedServices.forEach((service) => {
            servicesTotal += service.price;
            const itemEl = document.createElement('div');
            itemEl.className = 'flex justify-between items-center text-sm py-1 border-b border-outline-variant/10';
            itemEl.innerHTML = `
                <span class="text-primary font-medium">${service.name}</span>
                <span class="text-secondary font-semibold">${formatIDR(service.price)}</span>
            `;
            summaryItemsEl.appendChild(itemEl);
        });

        const total = servicesTotal * quantity;
        summaryQtyEl.textContent = `${quantity} pasang sepatu`;
        totalPriceEl.textContent = formatIDR(total);
    }

    bookBtn.addEventListener('click', () => {
        if (selectedServices.size === 0) {
            alert('Silakan pilih minimal satu layanan terlebih dahulu.');
            return;
        }

        let servicesListText = '';
        let servicesTotal = 0;

        selectedServices.forEach(service => {
            servicesListText += `- ${service.name} (${formatIDR(service.price)})\n`;
            servicesTotal += service.price;
        });

        const total = servicesTotal * quantity;
        const message = `Halo Bufflab Clean Shoes Surabaya! 👋\n\nSaya ingin memesan layanan perawatan sepatu:\n\n${servicesListText}\nJumlah: ${quantity} pasang sepatu\nEstimasi Total: ${formatIDR(total)}\n\nMohon informasi ketersediaan jadwal jemput/cuci. Terima kasih!`;
        
        window.open(getWhatsAppUrl(message), '_blank');
    });
}

// --- Claim Promo Listener ---
const promoClaimBtn = document.getElementById('promo-claim-btn');
if (promoClaimBtn) {
    promoClaimBtn.addEventListener('click', () => {
        const message = "Halo Bufflab Clean Shoes Surabaya! 👋\n\nSaya ingin mengambil Promo Spesial Cuci Sepatu 25K (Cuci 1 Gratis 1) yang saya lihat di website. Mohon info ketersediaan jadwal. Terima kasih!";
        window.open(getWhatsAppUrl(message), '_blank');
    });
}
