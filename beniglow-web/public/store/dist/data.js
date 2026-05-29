const WA_BASE = "https://wa.me/51993902669";
const waLink = text => `${WA_BASE}?text=${encodeURIComponent(text)}`;
const apiBaseUrl = (() => {
  const configured = window.BENIGLOW_API_BASE_URL;
  if (configured) return configured.replace(/\/$/, "");
  const host = window.location.hostname;
  const isLocalStatic = window.location.protocol === "file:" || (host === "127.0.0.1" || host === "localhost") && window.location.port !== "8000";
  return isLocalStatic ? "http://127.0.0.1:8000/api" : "/api";
})();
const soles = new Intl.NumberFormat("es-PE", {
  style: "currency",
  currency: "PEN",
  minimumFractionDigits: 2
});
const iconMap = {
  spa: "Sparkles",
  "wand-magic-sparkles": "Sparkles",
  "spray-can-sparkles": "Droplets",
  "pump-soap": "Droplets",
  brush: "Sparkles",
  gift: "Gift",
  leaf: "Leaf",
  "hand-holding-heart": "ShieldCheck",
  heart: "Sparkles",
  "bag-shopping": "Store",
  tags: "Gift",
  percent: "Gift"
};
function formatPrice(value) {
  const number = Number(value || 0);
  return soles.format(Number.isFinite(number) ? number : 0);
}
function productMessage(product) {
  const price = product.price || formatPrice(product.precio_final || product.precio);
  return `Hola, quiero pedir ${product.name || product.nombre} (${price}) de BeniGlow Store.`;
}
function mapProduct(product) {
  const details = [product.linea, product.tipo_piel ? `Piel: ${product.tipo_piel}` : null, product.acabado ? `Acabado: ${product.acabado}` : null, product.presentacion || product.volumen].filter(Boolean);
  return {
    id: product.slug || String(product.id),
    sourceId: product.id,
    slug: product.slug,
    brand: product.marca || "BeniGlow",
    name: product.nombre,
    category: product.categoria?.nombre || "Catálogo",
    price: formatPrice(product.precio_final ?? product.precio),
    oldPrice: product.en_oferta && product.precio ? formatPrice(product.precio) : null,
    badge: product.disponible ? "Stock" : "Sin stock",
    image: product.imagen_url || "/store/assets/img/logo-beniglow.png",
    benefits: details.length ? details.slice(0, 3) : ["Producto original", "Seleccionado para tu rutina", "Consulta disponibilidad"],
    whatsappMessage: productMessage({
      name: product.nombre,
      price: formatPrice(product.precio_final ?? product.precio)
    }),
    description: product.descripcion,
    ingredientes: product.ingredientes_clave,
    stock: Number(product.stock || 0),
    controlsStock: Boolean(product.controla_stock),
    available: Boolean(product.disponible),
    raw: product
  };
}
function mapCategory(category) {
  return {
    id: String(category.id),
    icon: iconMap[category.icono] || "Sparkles",
    title: category.nombre,
    description: category.descripcion || "Productos seleccionados para tu rutina.",
    raw: category
  };
}
function promotionHighlight(promo) {
  if (promo.tipo === "descuento_porcentaje") return `${Number(promo.valor).toFixed(0)}% de descuento`;
  if (promo.tipo === "descuento_fijo") return `${formatPrice(promo.valor)} de descuento`;
  if (promo.tipo === "precio_especial") return `Precio especial: ${formatPrice(promo.valor)}`;
  return promo.tipo?.toUpperCase?.() || "Promoción";
}
function mapPromotion(promo) {
  const target = promo.producto?.nombre || promo.categoria?.nombre || "productos seleccionados";
  return {
    id: String(promo.id),
    title: promo.nombre,
    description: promo.descripcion || `Promoción vigente para ${target}.`,
    badge: promo.tipo === "precio_especial" ? "Promo" : "Descuento",
    highlight: promotionHighlight(promo),
    raw: promo
  };
}
async function fetchJson(path, options = {}) {
  const response = await fetch(`${apiBaseUrl}${path}`, {
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      ...(window.BENIGLOW_STOREFRONT_TOKEN ? {
        "X-Storefront-Token": window.BENIGLOW_STOREFRONT_TOKEN
      } : {}),
      ...(options.headers || {})
    },
    ...options
  });
  if (!response.ok) {
    const text = await response.text();
    throw new Error(text || `Error HTTP ${response.status}`);
  }
  return response.json();
}
async function loadCatalog() {
  const [productsResponse, categoriesResponse, promosResponse] = await Promise.all([fetchJson("/catalogo/productos?per_page=100&con_stock=1"), fetchJson("/catalogo/categorias"), fetchJson("/catalogo/promociones")]);
  return {
    products: (productsResponse.data || []).map(mapProduct),
    categories: (categoriesResponse.data || []).map(mapCategory),
    promos: (promosResponse.data || []).map(mapPromotion)
  };
}
async function createOrder(payload) {
  return fetchJson("/pedidos-web", {
    method: "POST",
    body: JSON.stringify(payload)
  });
}
window.BENI = {
  api: {
    baseUrl: apiBaseUrl,
    loadCatalog,
    createOrder
  },
  whatsapp: {
    number: "993 902 669",
    international: "51993902669",
    base: WA_BASE,
    link: waLink,
    general: waLink("Hola, vengo de la web de BeniGlow Store. Quisiera información sobre productos de skincare."),
    productos: waLink("Hola, vengo de la web de BeniGlow Store. Quisiera consultar productos de skincare."),
    rutinas: waLink("Hola, quiero armar una rutina skincare con BeniGlow Store."),
    promos: waLink("Hola, quiero consultar las promociones de BeniGlow Store.")
  },
  brand: {
    name: "BeniGlow Store",
    email: "binitostore15@gmail.com",
    city: "Tacna, Perú",
    hours: "Atención 24 horas",
    facebook: "https://www.facebook.com/profile.php?id=61585059257252&locale=es_LA",
    instagram: "https://www.instagram.com/beniglow_store/"
  },
  products: [],
  categories: [],
  promos: [],
  routines: [{
    id: "basica",
    title: "Rutina básica de día",
    tagline: "Lo esencial, todos los días.",
    tone: "cream",
    icon: "Sun",
    steps: ["Limpiador", "Hidratante", "Bloqueador solar"],
    message: "Hola, quiero armar una rutina básica de día con BeniGlow Store."
  }, {
    id: "glow",
    title: "Rutina glow",
    tagline: "Piel luminosa, paso a paso.",
    tone: "rose",
    icon: "Sparkles",
    steps: ["Tónico", "Serum", "Hidratante", "Bloqueador"],
    message: "Hola, quiero armar una rutina glow con BeniGlow Store."
  }, {
    id: "sensible",
    title: "Rutina piel sensible",
    tagline: "Cuidado suave y calmante.",
    tone: "olive",
    icon: "Leaf",
    steps: ["Limpiador suave", "Producto calmante", "Hidratante", "Bloqueador ligero"],
    message: "Hola, quiero armar una rutina para piel sensible con BeniGlow Store."
  }],
  benefits: [{
    icon: "ShieldCheck",
    title: "Productos originales",
    text: "Seleccionados con cuidado para tu piel."
  }, {
    icon: "Plane",
    title: "Traídos desde YesStyle",
    text: "Marcas coreanas reconocidas en cada pedido."
  }, {
    icon: "MessageCircle",
    title: "Asesoría personalizada",
    text: "Te orientamos según tu tipo de piel."
  }, {
    icon: "Truck",
    title: "Envíos a todo el Perú",
    text: "Despachos seguros desde Tacna."
  }, {
    icon: "Store",
    title: "Entrega en tienda o punto",
    text: "Coordinamos la entrega contigo."
  }, {
    icon: "Clock",
    title: "Atención 24 horas",
    text: "Te respondemos lo antes posible."
  }, {
    icon: "Wallet",
    title: "Yape, Plin, transferencia y POS",
    text: "Paga como te quede mejor."
  }, {
    icon: "Sparkles",
    title: "Recomendaciones para tu piel",
    text: "Rutinas según tu necesidad."
  }],
  steps: [{
    n: "01",
    title: "Elige tus productos",
    text: "Revisa el catálogo y agrega tus favoritos al pedido."
  }, {
    n: "02",
    title: "Registra tu pedido",
    text: "Deja tus datos y coordinamos contigo la confirmación."
  }, {
    n: "03",
    title: "Confirmamos stock",
    text: "Validamos disponibilidad y forma de entrega."
  }, {
    n: "04",
    title: "Realiza el pago",
    text: "Yape, Plin, transferencia, efectivo o POS."
  }, {
    n: "05",
    title: "Recibe tu pedido",
    text: "Envío a todo el Perú o entrega en tienda."
  }],
  faq: [{
    q: "¿Los productos son originales?",
    a: "Sí. Trabajamos con productos originales y seleccionados, traídos desde YesStyle."
  }, {
    q: "¿Hacen envíos a todo el Perú?",
    a: "Sí. Atendemos desde Tacna y realizamos envíos seguros a todo el Perú."
  }, {
    q: "¿Puedo pedir recomendación para mi tipo de piel?",
    a: "Sí. Puedes escribirnos por WhatsApp y te orientamos según tu rutina, tipo de piel y necesidad."
  }, {
    q: "¿Tienen entrega en tienda?",
    a: "Sí. Podemos coordinar entrega en tienda o en un punto de entrega."
  }, {
    q: "¿Qué medios de pago aceptan?",
    a: "Aceptamos Yape, Plin, transferencia, efectivo y POS."
  }, {
    q: "¿Atienden las 24 horas?",
    a: "Sí. Puedes escribirnos en cualquier momento y responderemos lo antes posible."
  }, {
    q: "¿Tienen promociones?",
    a: "Sí. Las promociones y combos se actualizan según disponibilidad en tienda."
  }, {
    q: "¿Puedo comprar sin crear cuenta?",
    a: "Sí. Registras tu pedido con tus datos de contacto y luego coordinamos pago o entrega."
  }]
};