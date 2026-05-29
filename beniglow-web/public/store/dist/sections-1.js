const {
  useState: useStateS,
  useEffect: useEffectS
} = React;
function Navbar({
  cartCount = 0,
  onOpenCart
}) {
  const [scrolled, setScrolled] = useStateS(false);
  const [open, setOpen] = useStateS(false);
  useEffectS(() => {
    const fn = () => setScrolled(window.scrollY > 12);
    fn();
    window.addEventListener("scroll", fn, {
      passive: true
    });
    return () => window.removeEventListener("scroll", fn);
  }, []);
  const links = [["Inicio", "#inicio"], ["Productos", "#productos"], ["Rutinas", "#rutinas"], ["Promociones", "#promociones"], ["Cómo comprar", "#como-comprar"], ["Preguntas", "#faq"], ["Contacto", "#contacto"]];
  return /*#__PURE__*/React.createElement("header", {
    className: `fixed top-0 inset-x-0 z-50 transition-all duration-500 ${scrolled ? "backdrop-blur-xl bg-[color:var(--soft-cream)]/85 border-b border-[color:var(--border)]/80" : "bg-transparent"}`
  }, /*#__PURE__*/React.createElement("div", {
    className: "relative max-w-7xl w-full mx-auto px-5 md:px-8 h-[72px] flex items-center justify-between gap-4"
  }, /*#__PURE__*/React.createElement("a", {
    href: "#inicio",
    className: "flex items-center gap-2.5 shrink-0"
  }, /*#__PURE__*/React.createElement("img", {
    src: "/store/assets/img/logo-beniglow.png",
    alt: "BeniGlow Store",
    className: "w-10 h-10 object-contain"
  }), /*#__PURE__*/React.createElement("span", {
    className: "hidden sm:flex flex-col leading-none"
  }, /*#__PURE__*/React.createElement("span", {
    className: "font-serif text-lg text-[color:var(--coffee)] tracking-wide"
  }, "BeniGlow"), /*#__PURE__*/React.createElement("span", {
    className: "text-[10px] uppercase tracking-[0.32em] text-[color:var(--bronze)]"
  }, "Store"))), /*#__PURE__*/React.createElement("nav", {
    className: "hidden lg:flex items-center gap-1"
  }, links.map(([label, href]) => /*#__PURE__*/React.createElement("a", {
    key: href,
    href: href,
    className: "px-3.5 py-2 text-[13.5px] text-[color:var(--coffee)]/85 hover:text-[color:var(--olive)] transition rounded-full"
  }, label))), /*#__PURE__*/React.createElement("div", {
    className: "absolute right-5 top-1/2 -translate-y-1/2 lg:static lg:translate-y-0 flex items-center gap-1.5 sm:gap-2.5 shrink-0"
  }, /*#__PURE__*/React.createElement("button", {
    type: "button",
    onClick: onOpenCart,
    title: "Ver pedido web",
    className: "relative inline-flex h-10 items-center justify-center gap-2 rounded-full border border-[color:var(--olive)]/25 bg-white/95 px-3.5 text-[color:var(--olive)] shadow-[0_14px_30px_-12px_rgba(58,68,42,0.45)] backdrop-blur transition hover:-translate-y-0.5 hover:border-[color:var(--bronze)]/45 hover:bg-[color:var(--cream)] hover:shadow-[0_18px_38px_-12px_rgba(58,68,42,0.5)]",
    "aria-label": "Ver pedido web"
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "ShoppingBag",
    className: "w-4 h-4"
  }), /*#__PURE__*/React.createElement("span", {
    className: "hidden sm:inline text-[13px] font-medium"
  }, "Mi pedido"), cartCount > 0 ? /*#__PURE__*/React.createElement("span", {
    className: "absolute -top-1.5 -right-1.5 min-w-5 h-5 px-1 rounded-full bg-[color:var(--rose-gold)] text-white text-[11px] grid place-items-center ring-2 ring-[color:var(--soft-cream)]"
  }, cartCount) : null), /*#__PURE__*/React.createElement(WhatsAppButton, {
    label: "Comprar",
    size: "sm",
    className: "hidden sm:inline-flex"
  }), /*#__PURE__*/React.createElement("button", {
    onClick: () => setOpen(true),
    className: "lg:hidden w-9 h-9 sm:w-10 sm:h-10 grid place-items-center rounded-full border border-[color:var(--border)] bg-white/80 text-[color:var(--coffee)]",
    "aria-label": "Abrir men\xFA"
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "Menu",
    className: "w-5 h-5"
  })))), /*#__PURE__*/React.createElement("div", {
    className: `lg:hidden fixed inset-0 z-50 transition-opacity duration-300 ${open ? "opacity-100 pointer-events-auto" : "opacity-0 pointer-events-none"}`
  }, /*#__PURE__*/React.createElement("div", {
    className: "absolute inset-0 bg-[color:var(--coffee)]/30 backdrop-blur-sm",
    onClick: () => setOpen(false)
  }), /*#__PURE__*/React.createElement("div", {
    className: `absolute right-0 top-0 h-full w-[86%] max-w-sm bg-[color:var(--soft-cream)] shadow-2xl p-6 flex flex-col transition-transform duration-400 ${open ? "translate-x-0" : "translate-x-full"}`
  }, /*#__PURE__*/React.createElement("div", {
    className: "flex items-center justify-between mb-8"
  }, /*#__PURE__*/React.createElement("div", {
    className: "flex items-center gap-2.5"
  }, /*#__PURE__*/React.createElement("img", {
    src: "/store/assets/img/logo-beniglow.png",
    alt: "",
    className: "w-10 h-10 object-contain"
  }), /*#__PURE__*/React.createElement("span", {
    className: "font-serif text-lg text-[color:var(--coffee)]"
  }, "BeniGlow")), /*#__PURE__*/React.createElement("button", {
    onClick: () => setOpen(false),
    className: "w-10 h-10 grid place-items-center rounded-full border border-[color:var(--border)]",
    "aria-label": "Cerrar"
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "X",
    className: "w-5 h-5"
  }))), /*#__PURE__*/React.createElement("nav", {
    className: "flex flex-col gap-1"
  }, links.map(([label, href]) => /*#__PURE__*/React.createElement("a", {
    key: href,
    href: href,
    onClick: () => setOpen(false),
    className: "px-4 py-3.5 rounded-2xl text-[15px] text-[color:var(--coffee)] hover:bg-[color:var(--cream)] transition flex items-center justify-between"
  }, label, /*#__PURE__*/React.createElement(Icon, {
    name: "ArrowRight",
    className: "w-4 h-4 text-[color:var(--bronze)]"
  })))), /*#__PURE__*/React.createElement("div", {
    className: "mt-auto flex flex-col gap-3 pt-6"
  }, /*#__PURE__*/React.createElement("button", {
    type: "button",
    onClick: () => {
      setOpen(false);
      onOpenCart();
    },
    className: "inline-flex items-center justify-center gap-2 rounded-full px-5 py-3 text-sm font-medium text-white",
    style: {
      background: "linear-gradient(135deg, var(--olive), var(--bronze))"
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "ShoppingBag",
    className: "w-4 h-4"
  }), "Ver pedido ", cartCount ? `(${cartCount})` : ""), /*#__PURE__*/React.createElement(WhatsAppButton, {
    label: "Comprar por WhatsApp"
  }), /*#__PURE__*/React.createElement(GradientButton, {
    variant: "outline",
    href: "#productos",
    onClick: () => setOpen(false)
  }, "Ver cat\xE1logo")))));
}
function Hero({
  brands = []
}) {
  const [imageReady, setImageReady] = useStateS(false);
  const features = [{
    icon: "ShieldCheck",
    text: "Productos originales"
  }, {
    icon: "Truck",
    text: "Envíos a todo el Perú"
  }, {
    icon: "Clock",
    text: "Atención 24 horas"
  }, {
    icon: "Wallet",
    text: "Yape · Plin · POS"
  }];
  const visibleBrands = brands.slice(0, 4);
  const heroInitials = (visibleBrands.length ? visibleBrands : ["BeniGlow", "Store", "Glow"]).slice(0, 3).map(brand => brand.split(/\s+/).map(part => part[0]).join("").slice(0, 2).toLowerCase());
  return /*#__PURE__*/React.createElement("section", {
    id: "inicio",
    className: "relative w-full min-h-[100svh] lg:min-h-[100vh] overflow-hidden bg-[color:var(--soft-cream)]"
  }, /*#__PURE__*/React.createElement("div", {
    className: "absolute inset-0"
  }, /*#__PURE__*/React.createElement("div", {
    className: "absolute inset-0 hero-fallback"
  }), /*#__PURE__*/React.createElement("img", {
    src: "/store/assets/img/hero-products.webp",
    alt: "Seleccion de productos de skincare y cosmetica para BeniGlow Store",
    className: `absolute inset-0 w-full h-full object-cover hero-image ${imageReady ? "hero-image-ready hero-kenburns" : ""}`,
    style: {
      objectPosition: "right center"
    },
    loading: "eager",
    decoding: "async",
    fetchPriority: "high",
    onLoad: () => setImageReady(true),
    draggable: "false"
  }), /*#__PURE__*/React.createElement("div", {
    className: "hidden lg:block absolute inset-0",
    style: {
      background: "linear-gradient(90deg, rgba(255,253,248,0.92) 0%, rgba(255,253,248,0.78) 25%, rgba(255,253,248,0.35) 50%, rgba(255,253,248,0) 70%)"
    }
  }), /*#__PURE__*/React.createElement("div", {
    className: "lg:hidden absolute inset-0",
    style: {
      background: "linear-gradient(180deg, rgba(255,253,248,0.97) 0%, rgba(255,253,248,0.80) 30%, rgba(255,253,248,0.20) 55%, rgba(255,253,248,0) 75%)"
    }
  }), /*#__PURE__*/React.createElement("div", {
    className: "absolute pointer-events-none hero-pulse",
    style: {
      left: "5%",
      top: "28%",
      width: "45%",
      height: "55%",
      background: "radial-gradient(50% 50% at 50% 50%, hsl(20 38% 78% / .45), transparent 70%)",
      filter: "blur(28px)"
    }
  }), /*#__PURE__*/React.createElement(LeafSprig, {
    className: "absolute -left-6 top-[10%] w-44 lg:w-56 text-[color:var(--olive)]/30 animate-float-slow"
  }), /*#__PURE__*/React.createElement(LeafSprig, {
    className: "absolute -left-2 bottom-[6%] w-32 lg:w-40 text-[color:var(--rose-gold)]/40 rotate-180 animate-float-slow-rev hidden md:block"
  })), /*#__PURE__*/React.createElement("div", {
    className: "relative z-10 min-h-[100svh] lg:min-h-[100vh] flex items-center pt-28 pb-16 lg:pt-32 lg:pb-20"
  }, /*#__PURE__*/React.createElement("div", {
    className: "max-w-7xl w-full mx-auto px-5 md:px-8 lg:px-12"
  }, /*#__PURE__*/React.createElement("div", {
    className: "max-w-[560px] lg:max-w-[620px]"
  }, /*#__PURE__*/React.createElement(Reveal, {
    priority: true
  }, /*#__PURE__*/React.createElement("div", {
    className: "inline-flex items-center gap-2 rounded-full bg-white/85 backdrop-blur border border-[color:var(--border)] px-4 py-1.5 text-[11.5px] text-[color:var(--coffee)] mb-7 shadow-[0_8px_20px_-12px_rgba(58,68,42,0.25)]"
  }, /*#__PURE__*/React.createElement("span", {
    className: "w-1.5 h-1.5 rounded-full bg-[color:var(--olive)] animate-pulse"
  }), "Skincare coreano en Tacna \xB7 Env\xEDos a todo el Per\xFA")), /*#__PURE__*/React.createElement("h1", {
    className: "font-serif text-[44px] sm:text-[58px] lg:text-[72px] xl:text-[88px] leading-[0.96] text-[color:var(--coffee)] tracking-tight"
  }, /*#__PURE__*/React.createElement(Reveal, {
    as: "span",
    priority: true,
    className: "block overflow-hidden"
  }, /*#__PURE__*/React.createElement("span", {
    className: "block"
  }, "Tu rutina coreana")), /*#__PURE__*/React.createElement(Reveal, {
    as: "span",
    priority: true,
    delay: 70,
    className: "block overflow-hidden"
  }, /*#__PURE__*/React.createElement("span", {
    className: "block"
  }, "para una piel con")), /*#__PURE__*/React.createElement(Reveal, {
    as: "span",
    priority: true,
    delay: 130,
    className: "inline-block overflow-visible mt-1"
  }, /*#__PURE__*/React.createElement("span", {
    className: "relative inline-block"
  }, /*#__PURE__*/React.createElement("span", {
    className: "italic font-script-soft hero-shimmer",
    style: {
      background: "linear-gradient(120deg, var(--rose-gold) 0%, var(--bronze) 35%, var(--olive) 75%)",
      backgroundSize: "200% 100%",
      WebkitBackgroundClip: "text",
      backgroundClip: "text",
      color: "transparent"
    }
  }, "glow"), /*#__PURE__*/React.createElement("svg", {
    className: "absolute -bottom-2 left-0 w-full h-3 text-[color:var(--rose-gold)]/75",
    viewBox: "0 0 200 12",
    fill: "none",
    "aria-hidden": "true"
  }, /*#__PURE__*/React.createElement("path", {
    d: "M2 8 C 50 -2, 150 -2, 198 8",
    stroke: "currentColor",
    strokeWidth: "2.4",
    strokeLinecap: "round",
    className: "hero-stroke-draw",
    pathLength: "1"
  }))), /*#__PURE__*/React.createElement("span", {
    className: "text-[color:var(--coffee)]"
  }, "."))), /*#__PURE__*/React.createElement(Reveal, {
    priority: true,
    delay: 180
  }, /*#__PURE__*/React.createElement("p", {
    className: "mt-7 text-[16px] md:text-[18px] text-[color:var(--coffee)]/78 leading-relaxed max-w-[330px] sm:max-w-[520px]"
  }, "Bloqueadores, s\xE9rums, limpiadores y productos de skincare coreano seleccionados para cuidar tu piel todos los d\xEDas.")), /*#__PURE__*/React.createElement(Reveal, {
    priority: true,
    delay: 230
  }, /*#__PURE__*/React.createElement("ul", {
    className: "mt-7 flex flex-wrap gap-2 max-w-[540px]"
  }, features.map((f, i) => /*#__PURE__*/React.createElement("li", {
    key: f.text,
    className: "inline-flex items-center gap-2 rounded-full bg-white/90 backdrop-blur border border-[color:var(--border)] px-3.5 py-2 shadow-[0_8px_20px_-14px_rgba(58,68,42,0.3)] transition-all duration-300 hover:-translate-y-0.5 hover:border-[color:var(--olive)]/40",
    style: {
      animation: `chipIn .45s ${0.18 + i * 0.05}s both`
    }
  }, /*#__PURE__*/React.createElement("span", {
    className: "w-6 h-6 rounded-full grid place-items-center bg-[color:var(--cream)] text-[color:var(--olive)] flex-none"
  }, /*#__PURE__*/React.createElement(Icon, {
    name: f.icon,
    className: "w-3.5 h-3.5"
  })), /*#__PURE__*/React.createElement("span", {
    className: "text-[12.5px] text-[color:var(--coffee)] font-medium leading-tight whitespace-nowrap"
  }, f.text))))), /*#__PURE__*/React.createElement(Reveal, {
    priority: true,
    delay: 280
  }, /*#__PURE__*/React.createElement("div", {
    className: "mt-9 flex flex-wrap items-center gap-3"
  }, /*#__PURE__*/React.createElement(GradientButton, {
    href: "#productos",
    size: "lg",
    icon: /*#__PURE__*/React.createElement(Icon, {
      name: "ArrowRight",
      className: "w-4 h-4"
    })
  }, "Ver productos"), /*#__PURE__*/React.createElement(WhatsAppButton, {
    label: "Comprar por WhatsApp",
    variant: "outline",
    size: "lg"
  }))), /*#__PURE__*/React.createElement(Reveal, {
    priority: true,
    delay: 330
  }, /*#__PURE__*/React.createElement("div", {
    className: "mt-10 flex items-center gap-4"
  }, /*#__PURE__*/React.createElement("div", {
    className: "flex -space-x-2"
  }, ["#B9826A", "#314D2F", "#8A5E45"].map((c, i) => /*#__PURE__*/React.createElement("span", {
    key: i,
    className: "w-9 h-9 rounded-full border-2 border-[color:var(--soft-cream)] grid place-items-center text-[10px] font-serif text-white shadow-sm",
    style: {
      background: c
    }
  }, heroInitials[i] || "bg"))), /*#__PURE__*/React.createElement("div", {
    className: "leading-tight"
  }, /*#__PURE__*/React.createElement("p", {
    className: "text-[12.5px] text-[color:var(--coffee)] font-medium"
  }, visibleBrands.length ? `${visibleBrands.length}+ marcas seleccionadas` : "Marcas coreanas seleccionadas"), /*#__PURE__*/React.createElement("p", {
    className: "text-[11.5px] text-[color:var(--coffee)]/65"
  }, visibleBrands.length ? visibleBrands.join(" · ") : "Skincare, bloqueadores y rutinas para tu piel"))))))), /*#__PURE__*/React.createElement("div", {
    className: "hidden lg:block absolute top-[16%] right-[4%] xl:right-[8%] z-10 pointer-events-none",
    style: {
      animation: "fadeUp .8s .9s both"
    }
  }, /*#__PURE__*/React.createElement(FloatingCard, {
    icon: "ShieldCheck",
    title: "Originales",
    sub: "Marcas seleccionadas"
  })), /*#__PURE__*/React.createElement("div", {
    className: "hidden lg:block absolute bottom-[18%] right-[10%] xl:right-[14%] z-10 pointer-events-none",
    style: {
      animation: "fadeUp .8s 1.1s both"
    }
  }, /*#__PURE__*/React.createElement(FloatingCard, {
    icon: "Check",
    title: "Stock disponible",
    sub: "Listos para enviar",
    tone: "rose"
  })), /*#__PURE__*/React.createElement("div", {
    className: "hidden lg:flex absolute top-24 left-8 xl:left-12 items-center gap-2 bg-white/90 backdrop-blur rounded-full pl-1.5 pr-4 py-1.5 border border-[color:var(--border)] shadow-sm z-10",
    style: {
      animation: "fadeUp .7s .3s both"
    }
  }, /*#__PURE__*/React.createElement("img", {
    src: "/store/assets/img/logo-beniglow.png",
    alt: "",
    className: "w-8 h-8 object-contain"
  }), /*#__PURE__*/React.createElement("span", {
    className: "text-[10.5px] uppercase tracking-[0.28em] text-[color:var(--coffee)]"
  }, "K-Beauty \xB7 Tacna")), /*#__PURE__*/React.createElement("div", {
    className: "absolute bottom-6 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-2 text-[color:var(--coffee)]/55 hidden md:flex",
    style: {
      animation: "fadeUp .8s 1.3s both"
    }
  }, /*#__PURE__*/React.createElement("span", {
    className: "text-[10px] uppercase tracking-[0.32em]"
  }, "Desliza"), /*#__PURE__*/React.createElement("span", {
    className: "w-px h-12 bg-current relative overflow-hidden"
  }, /*#__PURE__*/React.createElement("span", {
    className: "absolute inset-x-0 top-0 h-1/3 bg-[color:var(--olive)] hero-scroll-dot"
  }))));
}
function BrandStrip({
  brands = []
}) {
  const fallbackBrands = ["Beauty of Joseon", "Anua", "COSRX", "Skin1004", "Round Lab", "Isntree", "Axis-Y"];
  const sourceBrands = [...new Set(brands.filter(Boolean))];
  const displayBrands = sourceBrands.length ? sourceBrands : fallbackBrands;
  const loop = displayBrands.concat(displayBrands, displayBrands);
  return /*#__PURE__*/React.createElement("section", {
    "aria-label": "Marcas que trabajamos",
    className: "border-y border-[color:var(--border)] bg-[color:var(--cream)]/40 py-7 overflow-hidden relative"
  }, /*#__PURE__*/React.createElement("div", {
    className: "max-w-7xl mx-auto px-5 md:px-8 flex items-center gap-6"
  }, /*#__PURE__*/React.createElement("span", {
    className: "text-[10.5px] uppercase tracking-[0.32em] text-[color:var(--bronze)] whitespace-nowrap hidden md:inline"
  }, "Marcas que trabajamos"), /*#__PURE__*/React.createElement("div", {
    className: "relative flex-1 overflow-hidden"
  }, /*#__PURE__*/React.createElement("div", {
    className: "absolute inset-y-0 left-0 w-16 z-10 pointer-events-none bg-gradient-to-r from-[color:var(--cream)]/40 to-transparent"
  }), /*#__PURE__*/React.createElement("div", {
    className: "absolute inset-y-0 right-0 w-16 z-10 pointer-events-none bg-gradient-to-l from-[color:var(--cream)]/40 to-transparent"
  }), /*#__PURE__*/React.createElement("div", {
    className: "flex items-center gap-10 md:gap-14 w-max animate-marquee-slow"
  }, loop.map((b, i) => /*#__PURE__*/React.createElement("span", {
    key: i,
    className: "font-serif text-[18px] md:text-[22px] text-[color:var(--coffee)]/55 italic tracking-wide whitespace-nowrap"
  }, b))))));
}
function FloatingCard({
  className,
  icon,
  title,
  sub,
  tone = "white"
}) {
  const tones = {
    white: "bg-white",
    rose: "bg-[color:var(--rose-gold)]/95 text-white",
    cream: "bg-[color:var(--cream)]"
  };
  const isRose = tone === "rose";
  return /*#__PURE__*/React.createElement("div", {
    className: `flex items-center gap-3 rounded-2xl ${tones[tone]} border border-[color:var(--border)]/70 shadow-[0_18px_30px_-20px_rgba(58,68,42,0.4)] px-3.5 py-2.5 backdrop-blur ${className} animate-float`
  }, /*#__PURE__*/React.createElement("span", {
    className: `w-9 h-9 rounded-xl grid place-items-center ${isRose ? "bg-white/15 text-white" : "bg-[color:var(--cream)] text-[color:var(--olive)]"} flex-none`
  }, /*#__PURE__*/React.createElement(Icon, {
    name: icon,
    className: "w-4 h-4"
  })), /*#__PURE__*/React.createElement("div", {
    className: "leading-tight pr-1"
  }, /*#__PURE__*/React.createElement("p", {
    className: `text-[12.5px] font-semibold ${isRose ? "text-white" : "text-[color:var(--coffee)]"}`
  }, title), /*#__PURE__*/React.createElement("p", {
    className: `text-[11px] ${isRose ? "text-white/85" : "text-[color:var(--muted-foreground)]"}`
  }, sub)));
}
function ProductMarquee({
  products = BENI.products,
  loading = false
}) {
  if (loading || products.length === 0) return null;
  const baseItems = products.slice(0, 8);
  const items = baseItems.concat(baseItems);
  return /*#__PURE__*/React.createElement("section", {
    className: "section-band section-cream product-marquee-section py-14 md:py-20 overflow-hidden relative"
  }, /*#__PURE__*/React.createElement("div", {
    className: "max-w-[1480px] mx-auto px-5 md:px-8"
  }, /*#__PURE__*/React.createElement("div", {
    className: "flex items-end justify-between gap-6 flex-wrap mb-8"
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
    className: "text-[11px] uppercase tracking-[0.25em] text-[color:var(--bronze)] mb-2"
  }, "Selecci\xF3n K-Beauty"), /*#__PURE__*/React.createElement("h3", {
    className: "font-serif text-2xl md:text-3xl text-[color:var(--coffee)]"
  }, "Lo que llegar\xE1 para tu piel")), /*#__PURE__*/React.createElement("a", {
    href: "#productos",
    className: "text-sm font-medium text-[color:var(--olive)] inline-flex items-center gap-1.5 hover:gap-2.5 transition-all"
  }, "Ver todo el cat\xE1logo ", /*#__PURE__*/React.createElement(Icon, {
    name: "ArrowRight",
    className: "w-4 h-4"
  })))), /*#__PURE__*/React.createElement("div", {
    className: "relative"
  }, /*#__PURE__*/React.createElement("div", {
    className: "absolute inset-y-0 left-0 w-24 md:w-40 z-10 pointer-events-none bg-gradient-to-r from-[color:var(--background)] to-transparent"
  }), /*#__PURE__*/React.createElement("div", {
    className: "absolute inset-y-0 right-0 w-24 md:w-40 z-10 pointer-events-none bg-gradient-to-l from-[color:var(--background)] to-transparent"
  }), /*#__PURE__*/React.createElement("div", {
    className: "overflow-hidden"
  }, /*#__PURE__*/React.createElement("div", {
    className: "flex gap-5 animate-marquee-slow w-max"
  }, items.map((p, i) => /*#__PURE__*/React.createElement("div", {
    key: i,
    className: "w-[220px] md:w-[260px] aspect-[3/4] rounded-3xl overflow-hidden bg-[color:var(--cream)] border border-[color:var(--border)] relative group"
  }, /*#__PURE__*/React.createElement(MarqueeProductImage, {
    product: p
  }), /*#__PURE__*/React.createElement("div", {
    className: "absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-[color:var(--coffee)]/65 to-transparent"
  }, /*#__PURE__*/React.createElement("p", {
    className: "text-[10px] uppercase tracking-[0.25em] text-white/85"
  }, p.brand), /*#__PURE__*/React.createElement("p", {
    className: "text-white text-sm font-medium line-clamp-1"
  }, p.name))))))));
}
function MarqueeProductImage({
  product
}) {
  const [loaded, setLoaded] = useStateS(false);
  return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("div", {
    className: `absolute inset-0 bg-gradient-to-br from-[color:var(--cream)] via-white to-[color:var(--soft-cream)] transition-opacity duration-500 ${loaded ? "opacity-0" : "opacity-100"}`
  }), /*#__PURE__*/React.createElement("img", {
    src: product.image,
    alt: product.name,
    className: `absolute inset-0 w-full h-full object-cover transition-[opacity,transform] duration-[900ms] ease-out group-hover:scale-105 ${loaded ? "opacity-100 scale-100" : "opacity-0 scale-[1.02]"}`,
    loading: "lazy",
    decoding: "async",
    onLoad: () => setLoaded(true)
  }));
}
function Categories({
  categories = BENI.categories,
  loading = false
}) {
  return /*#__PURE__*/React.createElement("section", {
    id: "categorias",
    className: "section-band section-soft py-20 md:py-28 relative"
  }, /*#__PURE__*/React.createElement(LeafSprig, {
    className: "absolute right-2 top-12 w-32 text-[color:var(--olive)]/15 hidden md:block"
  }), /*#__PURE__*/React.createElement("div", {
    className: "max-w-7xl mx-auto px-5 md:px-8"
  }, /*#__PURE__*/React.createElement(Reveal, null, /*#__PURE__*/React.createElement(SectionHeader, {
    eyebrow: "Categor\xEDas",
    title: "Encuentra lo que tu piel necesita",
    description: "Explora por categor\xEDa y consulta por WhatsApp para que te ayudemos a elegir el producto ideal para tu rutina."
  })), /*#__PURE__*/React.createElement("div", {
    className: "mt-12 md:mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6"
  }, loading ? [1, 2, 3, 4].map(i => /*#__PURE__*/React.createElement("div", {
    key: i,
    className: "h-48 rounded-3xl bg-white/70 border border-[color:var(--border)] animate-pulse"
  })) : null, !loading && categories.length === 0 ? /*#__PURE__*/React.createElement("div", {
    className: "sm:col-span-2 lg:col-span-4 rounded-3xl border border-dashed border-[color:var(--border)] bg-white/70 px-6 py-10 text-center"
  }, /*#__PURE__*/React.createElement("p", {
    className: "font-serif text-2xl text-[color:var(--coffee)]"
  }, "Categor\xEDas en preparaci\xF3n"), /*#__PURE__*/React.createElement("p", {
    className: "mt-2 text-sm text-[color:var(--muted-foreground)]"
  }, "Muy pronto ver\xE1s aqu\xED nuestras categor\xEDas favoritas para cuidar tu piel.")) : null, !loading && categories.map((c, i) => /*#__PURE__*/React.createElement(Reveal, {
    key: c.id,
    delay: i * 60
  }, /*#__PURE__*/React.createElement(CategoryCard, {
    icon: c.icon,
    title: c.title,
    description: c.description,
    message: `Hola, quiero consultar por ${c.title} en BeniGlow Store.`
  }))))));
}
function FeaturedProducts({
  products = BENI.products,
  loading = false,
  error = null,
  onDetail,
  onAddToCart
}) {
  const visibleProducts = products.slice(0, 8);
  return /*#__PURE__*/React.createElement("section", {
    id: "productos",
    className: "section-band section-blush py-20 md:py-28 relative"
  }, /*#__PURE__*/React.createElement("div", {
    className: "max-w-[1480px] mx-auto px-5 md:px-8"
  }, /*#__PURE__*/React.createElement(Reveal, null, /*#__PURE__*/React.createElement(SectionHeader, {
    eyebrow: "Productos destacados",
    title: "Favoritos para tu rutina diaria",
    description: "Productos seleccionados para proteger, hidratar y cuidar tu piel con f\xF3rmulas ligeras y efectivas."
  })), /*#__PURE__*/React.createElement("div", {
    className: "mt-12 md:mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6"
  }, loading ? [1, 2, 3, 4].map(i => /*#__PURE__*/React.createElement("div", {
    key: i,
    className: "h-[420px] rounded-[28px] bg-white/70 border border-[color:var(--border)] animate-pulse"
  })) : null, !loading && error ? /*#__PURE__*/React.createElement("div", {
    className: "sm:col-span-2 lg:col-span-4 rounded-3xl border border-red-100 bg-red-50 px-6 py-8 text-center"
  }, /*#__PURE__*/React.createElement("p", {
    className: "font-serif text-2xl text-red-900"
  }, "No se pudo cargar el cat\xE1logo"), /*#__PURE__*/React.createElement("p", {
    className: "mt-2 text-sm text-red-700"
  }, error)) : null, !loading && !error && products.length === 0 ? /*#__PURE__*/React.createElement("div", {
    className: "sm:col-span-2 lg:col-span-4 rounded-3xl border border-dashed border-[color:var(--border)] bg-white/70 px-6 py-10 text-center"
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "ShoppingBag",
    className: "w-10 h-10 mx-auto text-[color:var(--bronze)]"
  }), /*#__PURE__*/React.createElement("p", {
    className: "mt-4 font-serif text-3xl text-[color:var(--coffee)]"
  }, "Pronto tendremos nuevos favoritos"), /*#__PURE__*/React.createElement("p", {
    className: "mt-2 text-sm text-[color:var(--muted-foreground)]"
  }, "Estamos preparando productos seleccionados para que armes tu rutina con confianza.")) : null, !loading && !error && visibleProducts.map((p, i) => /*#__PURE__*/React.createElement(Reveal, {
    key: p.id,
    delay: i * 80
  }, /*#__PURE__*/React.createElement(ProductCard, {
    product: p,
    onDetail: onDetail,
    onAddToCart: onAddToCart
  })))), /*#__PURE__*/React.createElement("div", {
    className: "mt-12 text-center"
  }, /*#__PURE__*/React.createElement(WhatsAppButton, {
    label: "Consultar el cat\xE1logo completo",
    message: BENI.whatsapp.general ? "Hola, quiero ver el catálogo completo de BeniGlow Store." : "",
    variant: "outline",
    size: "lg"
  }), /*#__PURE__*/React.createElement("p", {
    className: "mt-4 text-xs text-[color:var(--muted-foreground)]"
  }, "Estos productos son apoyo a tu rutina diaria. Si tienes piel sensible o alguna condici\xF3n dermatol\xF3gica, consulta con un especialista."))));
}
function SunProtection() {
  const items = [{
    icon: "ShieldCheck",
    title: "Protección UVA/UVB",
    text: "Defensa amplia para tu rutina diaria."
  }, {
    icon: "Cloud",
    title: "Fórmulas ligeras",
    text: "Texturas suaves que no se sienten pesadas."
  }, {
    icon: "Sparkles",
    title: "Acabado natural",
    text: "Sin brillos exagerados ni residuos."
  }, {
    icon: "Sun",
    title: "Ideal para uso diario",
    text: "Pensado como último paso de tu rutina de día."
  }, {
    icon: "Droplets",
    title: "Opciones hidratantes",
    text: "Aportan confort y sensación fresca."
  }, {
    icon: "Clock",
    title: "Reaplicación durante el día",
    text: "Refresca según tu exposición solar."
  }];
  return /*#__PURE__*/React.createElement("section", {
    className: "section-band section-sage py-20 md:py-28 relative overflow-hidden"
  }, /*#__PURE__*/React.createElement("div", {
    className: "absolute inset-0 -z-10",
    style: {
      background: "radial-gradient(50% 50% at 50% 0%, hsl(20 38% 88% / .55), transparent 60%), var(--soft-cream)"
    }
  }), /*#__PURE__*/React.createElement(LeafSprig, {
    className: "absolute left-6 top-1/2 -translate-y-1/2 w-44 text-[color:var(--olive)]/15 hidden md:block"
  }), /*#__PURE__*/React.createElement(LeafSprig, {
    className: "absolute right-6 top-1/4 w-32 text-[color:var(--rose-gold)]/15 rotate-180 hidden md:block"
  }), /*#__PURE__*/React.createElement("div", {
    className: "max-w-7xl mx-auto px-5 md:px-8"
  }, /*#__PURE__*/React.createElement(Reveal, null, /*#__PURE__*/React.createElement(SectionHeader, {
    eyebrow: "Protecci\xF3n solar",
    title: "Protege tu piel todos los d\xEDas",
    description: "Bloqueadores coreanos de textura ligera, ideales para acompa\xF1ar tu rutina diaria."
  })), /*#__PURE__*/React.createElement("div", {
    className: "mt-12 md:mt-16 grid sm:grid-cols-2 lg:grid-cols-3 gap-5"
  }, items.map((it, i) => /*#__PURE__*/React.createElement(Reveal, {
    key: it.title,
    delay: i * 60
  }, /*#__PURE__*/React.createElement("div", {
    className: "h-full p-7 rounded-3xl bg-white/80 backdrop-blur border border-[color:var(--border)] flex gap-4 items-start hover:bg-white transition-colors"
  }, /*#__PURE__*/React.createElement("span", {
    className: "w-11 h-11 rounded-2xl grid place-items-center bg-[color:var(--cream)] text-[color:var(--olive)] flex-none"
  }, /*#__PURE__*/React.createElement(Icon, {
    name: it.icon,
    className: "w-5 h-5"
  })), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("h4", {
    className: "font-serif text-lg text-[color:var(--coffee)]"
  }, it.title), /*#__PURE__*/React.createElement("p", {
    className: "text-sm text-[color:var(--muted-foreground)] mt-1.5 leading-relaxed"
  }, it.text)))))), /*#__PURE__*/React.createElement(Reveal, null, /*#__PURE__*/React.createElement("p", {
    className: "mt-10 mx-auto max-w-3xl text-center text-[13px] text-[color:var(--muted-foreground)] leading-relaxed rounded-3xl bg-[color:var(--cream)]/60 border border-[color:var(--border)] px-6 py-4"
  }, "El protector solar debe aplicarse como \xFAltimo paso de la rutina de d\xEDa y reaplicarse seg\xFAn exposici\xF3n solar. Si tienes piel sensible o alguna condici\xF3n dermatol\xF3gica, consulta con un especialista."))));
}
Object.assign(window, {
  Navbar,
  Hero,
  BrandStrip,
  ProductMarquee,
  MarqueeProductImage,
  Categories,
  FeaturedProducts,
  SunProtection
});