const {
  useState: useStateB,
  useMemo: useMemoB
} = React;
function Routines() {
  const tones = {
    cream: {
      bg: "linear-gradient(160deg, #FFF5E8, #FBE8D4)",
      pill: "bg-[color:var(--bronze)]/15 text-[color:var(--coffee)]",
      icon: "text-[color:var(--bronze)]"
    },
    rose: {
      bg: "linear-gradient(160deg, #FCE7DC, #F1C7B0)",
      pill: "bg-[color:var(--rose-gold)]/20 text-[color:var(--coffee)]",
      icon: "text-[color:var(--rose-gold)]"
    },
    olive: {
      bg: "linear-gradient(160deg, #E8EFE0, #CFDCC2)",
      pill: "bg-[color:var(--olive)]/15 text-[color:var(--olive)]",
      icon: "text-[color:var(--olive)]"
    }
  };
  return /*#__PURE__*/React.createElement("section", {
    id: "rutinas",
    className: "section-band section-soft py-20 md:py-28"
  }, /*#__PURE__*/React.createElement("div", {
    className: "max-w-7xl mx-auto px-5 md:px-8"
  }, /*#__PURE__*/React.createElement(Reveal, null, /*#__PURE__*/React.createElement(SectionHeader, {
    eyebrow: "Rutinas",
    title: "Rutinas simples para empezar",
    description: "Pasos b\xE1sicos para construir tu rutina de d\xEDa y de noche, seg\xFAn lo que tu piel necesita."
  })), /*#__PURE__*/React.createElement("div", {
    className: "mt-12 md:mt-16 grid md:grid-cols-3 gap-5 md:gap-6"
  }, BENI.routines.map((r, i) => {
    const t = tones[r.tone];
    return /*#__PURE__*/React.createElement(Reveal, {
      key: r.id,
      delay: i * 90
    }, /*#__PURE__*/React.createElement("article", {
      className: "relative h-full p-7 md:p-8 rounded-[32px] overflow-hidden border border-[color:var(--border)]/70 group transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_36px_60px_-30px_rgba(58,68,42,0.35)]",
      style: {
        background: t.bg
      }
    }, /*#__PURE__*/React.createElement(LeafSprig, {
      className: `absolute -right-4 -top-4 w-28 ${t.icon} opacity-40`
    }), /*#__PURE__*/React.createElement("span", {
      className: `inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10.5px] font-semibold uppercase tracking-[0.18em] ${t.pill}`
    }, "Rutina ", String(i + 1).padStart(2, "0")), /*#__PURE__*/React.createElement("h3", {
      className: "mt-5 font-serif text-2xl md:text-3xl text-[color:var(--coffee)] leading-tight"
    }, r.title), /*#__PURE__*/React.createElement("p", {
      className: "mt-2 text-sm text-[color:var(--coffee)]/75"
    }, r.tagline), /*#__PURE__*/React.createElement("ol", {
      className: "mt-6 space-y-2.5 relative"
    }, r.steps.map((s, j) => /*#__PURE__*/React.createElement("li", {
      key: s,
      className: "flex items-center gap-3 bg-white/65 backdrop-blur rounded-2xl px-3.5 py-2.5 border border-white/60"
    }, /*#__PURE__*/React.createElement("span", {
      className: `w-7 h-7 rounded-full grid place-items-center text-[12px] font-semibold bg-white ${t.icon}`
    }, String(j + 1).padStart(2, "0")), /*#__PURE__*/React.createElement("span", {
      className: "text-[14px] text-[color:var(--coffee)] font-medium"
    }, s)))), /*#__PURE__*/React.createElement("div", {
      className: "mt-7"
    }, /*#__PURE__*/React.createElement(WhatsAppButton, {
      label: "Quiero esta rutina",
      message: r.message,
      size: "sm",
      variant: "solid"
    }))));
  }))));
}
function Promos({
  promos = BENI.promos,
  loading = false
}) {
  return /*#__PURE__*/React.createElement("section", {
    id: "promociones",
    className: "section-band section-cream py-20 md:py-28 relative overflow-hidden"
  }, /*#__PURE__*/React.createElement("div", {
    className: "absolute inset-0 -z-10",
    style: {
      background: "linear-gradient(180deg, var(--soft-cream), hsl(20 42% 96%) 52%, var(--cream))"
    }
  }), /*#__PURE__*/React.createElement("div", {
    className: "max-w-7xl mx-auto px-5 md:px-8"
  }, /*#__PURE__*/React.createElement(Reveal, null, /*#__PURE__*/React.createElement(SectionHeader, {
    eyebrow: "Promociones",
    title: "Promoci\xF3n para cuidar tu piel",
    description: "Campa\xF1as y combos seleccionados para empezar o reforzar tu rutina. Sujetos a stock disponible."
  })), /*#__PURE__*/React.createElement("div", {
    className: "mt-12 md:mt-16 grid sm:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6"
  }, loading ? [1, 2, 3, 4].map(i => /*#__PURE__*/React.createElement("div", {
    key: i,
    className: "h-64 rounded-[28px] bg-white/70 border border-[color:var(--border)] animate-pulse"
  })) : null, !loading && promos.length === 0 ? /*#__PURE__*/React.createElement("div", {
    className: "sm:col-span-2 lg:col-span-3 rounded-3xl border border-dashed border-[color:var(--border)] bg-white/70 px-6 py-10 text-center"
  }, /*#__PURE__*/React.createElement("p", {
    className: "font-serif text-2xl text-[color:var(--coffee)]"
  }, "Promociones en preparaci\xF3n"), /*#__PURE__*/React.createElement("p", {
    className: "mt-2 text-sm text-[color:var(--muted-foreground)]"
  }, "Pronto encontrar\xE1s aqu\xED promociones y combos seleccionados para tu rutina.")) : null, !loading && promos.map((p, i) => /*#__PURE__*/React.createElement(Reveal, {
    key: p.id,
    delay: i * 90
  }, /*#__PURE__*/React.createElement("a", {
    href: BENI.whatsapp.link(`Hola, quiero consultar la promoción "${p.title}" de BeniGlow Store.`),
    target: "_blank",
    rel: "noopener",
    className: `group block h-full rounded-[30px] bg-white border border-[color:var(--border)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_34px_70px_-32px_rgba(58,68,42,0.42)] relative overflow-hidden ${promos.length === 1 ? "sm:col-span-2 lg:col-span-3 p-8 md:p-10" : "p-7"}`
  }, /*#__PURE__*/React.createElement("div", {
    className: "absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none",
    style: {
      background: "radial-gradient(60% 55% at 85% 20%, hsl(20 38% 76% / .28), transparent 65%)"
    }
  }), /*#__PURE__*/React.createElement("div", {
    className: "relative flex items-center justify-between gap-3"
  }, /*#__PURE__*/React.createElement(Pill, {
    tone: p.badge === "Promo" ? "rose" : "olive"
  }, p.badge), /*#__PURE__*/React.createElement(Icon, {
    name: "Gift",
    className: "w-5 h-5 text-[color:var(--bronze)]"
  })), /*#__PURE__*/React.createElement("h3", {
    className: `relative mt-5 font-serif text-[color:var(--coffee)] leading-snug ${promos.length === 1 ? "text-3xl md:text-5xl max-w-3xl" : "text-xl md:text-2xl"}`
  }, p.title), /*#__PURE__*/React.createElement("p", {
    className: `relative mt-3 text-[color:var(--muted-foreground)] leading-relaxed ${promos.length === 1 ? "text-base max-w-2xl" : "text-sm"}`
  }, p.description), p.highlight ? /*#__PURE__*/React.createElement("div", {
    className: `relative mt-6 rounded-3xl px-5 py-4 text-center border border-white/20 ${promos.length === 1 ? "md:max-w-sm" : ""}`,
    style: {
      background: "linear-gradient(135deg, var(--olive), #1f3520)"
    }
  }, /*#__PURE__*/React.createElement("p", {
    className: "text-[11px] uppercase tracking-[0.25em] text-white/75"
  }, "Campa\xF1a"), /*#__PURE__*/React.createElement("p", {
    className: `font-serif text-white ${promos.length === 1 ? "text-4xl" : "text-2xl"}`
  }, p.highlight)) : null, /*#__PURE__*/React.createElement("span", {
    className: "relative mt-6 inline-flex items-center gap-1.5 text-sm font-medium text-[color:var(--olive)]"
  }, "Consultar por WhatsApp ", /*#__PURE__*/React.createElement(Icon, {
    name: "ArrowRight",
    className: "w-4 h-4 transition-transform group-hover:translate-x-1"
  })))))), /*#__PURE__*/React.createElement("p", {
    className: "mt-10 text-center text-[12.5px] text-[color:var(--muted-foreground)]"
  }, "Promociones sujetas a stock y disponibilidad. Consulta por WhatsApp.")));
}
function Benefits() {
  return /*#__PURE__*/React.createElement("section", {
    className: "section-band section-blush py-20 md:py-28"
  }, /*#__PURE__*/React.createElement("div", {
    className: "max-w-7xl mx-auto px-5 md:px-8"
  }, /*#__PURE__*/React.createElement(Reveal, null, /*#__PURE__*/React.createElement(SectionHeader, {
    eyebrow: "Por qu\xE9 nosotras",
    title: "Por qu\xE9 comprar en BeniGlow",
    description: "Un servicio cercano y honesto: te ayudamos a elegir seg\xFAn tu piel, sin promesas exageradas."
  })), /*#__PURE__*/React.createElement("div", {
    className: "mt-12 md:mt-16 grid sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5"
  }, BENI.benefits.map((b, i) => /*#__PURE__*/React.createElement(Reveal, {
    key: b.title,
    delay: i * 50
  }, /*#__PURE__*/React.createElement("div", {
    className: "h-full p-6 rounded-3xl bg-white border border-[color:var(--border)] hover:border-[color:var(--olive)]/30 transition-colors"
  }, /*#__PURE__*/React.createElement("span", {
    className: "w-12 h-12 rounded-2xl grid place-items-center bg-gradient-to-br from-[color:var(--cream)] to-white text-[color:var(--olive)] mb-4 border border-[color:var(--border)]/60"
  }, /*#__PURE__*/React.createElement(Icon, {
    name: b.icon,
    className: "w-5 h-5"
  })), /*#__PURE__*/React.createElement("h4", {
    className: "font-serif text-[17px] text-[color:var(--coffee)]"
  }, b.title), /*#__PURE__*/React.createElement("p", {
    className: "text-sm text-[color:var(--muted-foreground)] mt-1.5 leading-relaxed"
  }, b.text)))))));
}
function HowToBuy() {
  return /*#__PURE__*/React.createElement("section", {
    id: "como-comprar",
    className: "section-band py-20 md:py-28 relative overflow-hidden"
  }, /*#__PURE__*/React.createElement("div", {
    className: "absolute inset-0 -z-10",
    style: {
      background: "linear-gradient(180deg, var(--soft-cream), var(--background))"
    }
  }), /*#__PURE__*/React.createElement("div", {
    className: "max-w-7xl mx-auto px-5 md:px-8"
  }, /*#__PURE__*/React.createElement(Reveal, null, /*#__PURE__*/React.createElement(SectionHeader, {
    eyebrow: "Proceso",
    title: "Comprar es f\xE1cil",
    description: "Pedidos directos por WhatsApp en 5 pasos simples."
  })), /*#__PURE__*/React.createElement("div", {
    className: "mt-14 hidden md:block"
  }, /*#__PURE__*/React.createElement("div", {
    className: "relative"
  }, /*#__PURE__*/React.createElement("div", {
    className: "absolute top-9 left-[6%] right-[6%] h-px",
    style: {
      background: "linear-gradient(90deg, transparent, var(--border), var(--border), transparent)"
    }
  }), /*#__PURE__*/React.createElement("div", {
    className: "grid grid-cols-5 gap-4"
  }, BENI.steps.map((s, i) => /*#__PURE__*/React.createElement(Reveal, {
    key: s.n,
    delay: i * 100
  }, /*#__PURE__*/React.createElement("div", {
    className: "text-center px-3"
  }, /*#__PURE__*/React.createElement("span", {
    className: "relative inline-grid place-items-center w-[72px] h-[72px] rounded-full font-serif text-xl text-[color:var(--olive)]",
    style: {
      background: "linear-gradient(135deg, #fff, var(--cream))",
      boxShadow: "0 12px 30px -16px rgba(58,68,42,0.35)",
      border: "1px solid var(--border)"
    }
  }, s.n, /*#__PURE__*/React.createElement("span", {
    className: "absolute -bottom-1 -right-1 w-3 h-3 rounded-full bg-[color:var(--rose-gold)]"
  })), /*#__PURE__*/React.createElement("h4", {
    className: "font-serif text-lg text-[color:var(--coffee)] mt-5"
  }, s.title), /*#__PURE__*/React.createElement("p", {
    className: "text-sm text-[color:var(--muted-foreground)] mt-1.5 leading-relaxed"
  }, s.text))))))), /*#__PURE__*/React.createElement("div", {
    className: "mt-12 md:hidden relative pl-6"
  }, /*#__PURE__*/React.createElement("span", {
    className: "absolute left-[14px] top-2 bottom-2 w-px bg-[color:var(--border)]"
  }), BENI.steps.map((s, i) => /*#__PURE__*/React.createElement(Reveal, {
    key: s.n,
    delay: i * 80
  }, /*#__PURE__*/React.createElement("div", {
    className: "relative pb-7"
  }, /*#__PURE__*/React.createElement("span", {
    className: "absolute -left-[19px] top-0 w-9 h-9 rounded-full grid place-items-center font-serif text-sm text-[color:var(--olive)] bg-white border border-[color:var(--border)]"
  }, s.n), /*#__PURE__*/React.createElement("div", {
    className: "pl-7"
  }, /*#__PURE__*/React.createElement("h4", {
    className: "font-serif text-lg text-[color:var(--coffee)]"
  }, s.title), /*#__PURE__*/React.createElement("p", {
    className: "text-sm text-[color:var(--muted-foreground)] mt-1 leading-relaxed"
  }, s.text))))))));
}
function CtaFinal() {
  return /*#__PURE__*/React.createElement("section", {
    className: "section-band section-soft py-20 md:py-28"
  }, /*#__PURE__*/React.createElement("div", {
    className: "max-w-6xl mx-auto px-5 md:px-8"
  }, /*#__PURE__*/React.createElement(Reveal, null, /*#__PURE__*/React.createElement("div", {
    className: "relative overflow-hidden rounded-[40px] p-10 md:p-16 border border-[color:var(--border)]",
    style: {
      background: "radial-gradient(120% 90% at 100% 0%, hsl(20 38% 78% / .55), transparent 55%), radial-gradient(80% 80% at 0% 100%, hsl(95 28% 55% / .25), transparent 55%), linear-gradient(180deg, var(--soft-cream), var(--cream))"
    }
  }, /*#__PURE__*/React.createElement(LeafSprig, {
    className: "absolute -right-2 -top-4 w-40 text-[color:var(--olive)]/30"
  }), /*#__PURE__*/React.createElement(LeafSprig, {
    className: "absolute -left-2 bottom-2 w-32 text-[color:var(--rose-gold)]/30 rotate-180"
  }), /*#__PURE__*/React.createElement("div", {
    className: "relative max-w-3xl"
  }, /*#__PURE__*/React.createElement("p", {
    className: "text-[11px] uppercase tracking-[0.28em] text-[color:var(--bronze)] mb-4"
  }, "Empieza hoy"), /*#__PURE__*/React.createElement("h2", {
    className: "font-serif text-[36px] md:text-[56px] leading-[1.02] text-[color:var(--coffee)] tracking-tight"
  }, "\xBFLista para empezar tu rutina con", " ", /*#__PURE__*/React.createElement("span", {
    className: "italic font-script-soft",
    style: {
      background: "linear-gradient(120deg, var(--rose-gold), var(--olive))",
      WebkitBackgroundClip: "text",
      backgroundClip: "text",
      color: "transparent"
    }
  }, "glow"), "?"), /*#__PURE__*/React.createElement("p", {
    className: "mt-6 text-base md:text-lg text-[color:var(--muted-foreground)] max-w-xl leading-relaxed"
  }, "Escr\xEDbenos y te ayudamos a elegir productos seg\xFAn tu piel, rutina y presupuesto."), /*#__PURE__*/React.createElement("div", {
    className: "mt-8 flex flex-wrap items-center gap-3"
  }, /*#__PURE__*/React.createElement(WhatsAppButton, {
    label: "Comprar por WhatsApp",
    size: "lg"
  }), /*#__PURE__*/React.createElement(GradientButton, {
    href: "#productos",
    variant: "outline",
    size: "lg"
  }, "Ver productos")))))));
}
function FaqSection() {
  return /*#__PURE__*/React.createElement("section", {
    id: "faq",
    className: "section-band section-cream py-20 md:py-28"
  }, /*#__PURE__*/React.createElement("div", {
    className: "max-w-5xl mx-auto px-5 md:px-8"
  }, /*#__PURE__*/React.createElement(Reveal, null, /*#__PURE__*/React.createElement(SectionHeader, {
    eyebrow: "Preguntas frecuentes",
    title: "Resolvamos tus dudas",
    description: "Si no encuentras lo que buscas, escr\xEDbenos por WhatsApp y te respondemos en cualquier momento."
  })), /*#__PURE__*/React.createElement(Reveal, null, /*#__PURE__*/React.createElement("div", {
    className: "mt-12"
  }, /*#__PURE__*/React.createElement(FAQAccordion, {
    items: BENI.faq
  })))));
}
function ContactSection() {
  const [form, setForm] = useStateB({
    nombre: "",
    celular: "",
    producto: "",
    piel: "",
    mensaje: ""
  });
  const onChange = k => e => setForm(f => ({
    ...f,
    [k]: e.target.value
  }));
  const message = useMemoB(() => {
    const lines = ["Hola BeniGlow Store, vengo de la web.", form.nombre && `Nombre: ${form.nombre}`, form.celular && `Celular: ${form.celular}`, form.producto && `Busco: ${form.producto}`, form.piel && `Tipo de piel: ${form.piel}`, form.mensaje && `Mensaje: ${form.mensaje}`].filter(Boolean);
    return lines.join("\n");
  }, [form]);
  const onSubmit = e => {
    e.preventDefault();
    window.open(BENI.whatsapp.link(message), "_blank", "noopener");
  };
  const tiposPiel = ["", "Grasa", "Mixta", "Seca", "Sensible", "No estoy segura"];
  return /*#__PURE__*/React.createElement("section", {
    id: "contacto",
    className: "section-band py-20 md:py-28 relative"
  }, /*#__PURE__*/React.createElement("div", {
    className: "absolute inset-0 -z-10",
    style: {
      background: "linear-gradient(180deg, var(--background), var(--soft-cream))"
    }
  }), /*#__PURE__*/React.createElement("div", {
    className: "max-w-7xl mx-auto px-5 md:px-8 grid lg:grid-cols-[1fr_1.1fr] gap-12 lg:gap-16"
  }, /*#__PURE__*/React.createElement(Reveal, null, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
    className: "text-[11px] uppercase tracking-[0.28em] text-[color:var(--bronze)] mb-4"
  }, "Contacto"), /*#__PURE__*/React.createElement("h2", {
    className: "font-serif text-3xl md:text-5xl text-[color:var(--coffee)] leading-[1.05] tracking-tight"
  }, "Conversemos por WhatsApp"), /*#__PURE__*/React.createElement("p", {
    className: "mt-5 text-[color:var(--muted-foreground)] leading-relaxed max-w-md"
  }, "Cu\xE9ntanos qu\xE9 buscas, tu tipo de piel y cualquier duda. Te respondemos lo antes posible."), /*#__PURE__*/React.createElement("ul", {
    className: "mt-9 space-y-4"
  }, [["WhatsApp", "Icon:WhatsApp", BENI.whatsapp.number, BENI.whatsapp.general], ["Correo", "Mail", BENI.brand.email, `mailto:${BENI.brand.email}`], ["Ubicación", "MapPin", `${BENI.brand.city} · Envíos a todo el Perú`, null], ["Horario", "Clock", BENI.brand.hours, null]].map(([label, icon, val, href]) => {
    const iconName = icon.replace("Icon:", "");
    const Inner = /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("span", {
      className: "w-11 h-11 rounded-2xl grid place-items-center bg-[color:var(--cream)] text-[color:var(--olive)] flex-none"
    }, /*#__PURE__*/React.createElement(Icon, {
      name: iconName,
      className: "w-5 h-5"
    })), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
      className: "text-[11px] uppercase tracking-[0.2em] text-[color:var(--muted-foreground)]"
    }, label), /*#__PURE__*/React.createElement("p", {
      className: "text-[15px] text-[color:var(--coffee)] font-medium"
    }, val)));
    return /*#__PURE__*/React.createElement("li", {
      key: label
    }, href ? /*#__PURE__*/React.createElement("a", {
      href: href,
      target: "_blank",
      rel: "noopener",
      className: "flex items-center gap-4 p-4 rounded-2xl bg-white/70 border border-[color:var(--border)] hover:bg-white transition"
    }, Inner) : /*#__PURE__*/React.createElement("div", {
      className: "flex items-center gap-4 p-4 rounded-2xl bg-white/70 border border-[color:var(--border)]"
    }, Inner));
  })), /*#__PURE__*/React.createElement("div", {
    className: "mt-7 flex items-center gap-3"
  }, /*#__PURE__*/React.createElement("a", {
    href: BENI.brand.instagram,
    target: "_blank",
    rel: "noopener",
    className: "w-11 h-11 grid place-items-center rounded-full bg-white border border-[color:var(--border)] text-[color:var(--rose-gold)] hover:text-white hover:bg-[color:var(--rose-gold)] transition",
    "aria-label": "Instagram"
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "Instagram",
    className: "w-5 h-5"
  })), /*#__PURE__*/React.createElement("a", {
    href: BENI.brand.facebook,
    target: "_blank",
    rel: "noopener",
    className: "w-11 h-11 grid place-items-center rounded-full bg-white border border-[color:var(--border)] text-[color:var(--olive)] hover:text-white hover:bg-[color:var(--olive)] transition",
    "aria-label": "Facebook"
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "Facebook",
    className: "w-5 h-5"
  }))))), /*#__PURE__*/React.createElement(Reveal, {
    delay: 120
  }, /*#__PURE__*/React.createElement("form", {
    onSubmit: onSubmit,
    className: "rounded-[32px] bg-white border border-[color:var(--border)] p-7 md:p-10 shadow-[0_30px_60px_-40px_rgba(58,68,42,0.45)]"
  }, /*#__PURE__*/React.createElement("p", {
    className: "text-[11px] uppercase tracking-[0.25em] text-[color:var(--bronze)] mb-2"
  }, "Env\xEDanos un mensaje"), /*#__PURE__*/React.createElement("h3", {
    className: "font-serif text-2xl md:text-3xl text-[color:var(--coffee)]"
  }, "Cu\xE9ntanos lo que buscas"), /*#__PURE__*/React.createElement("div", {
    className: "mt-7 grid sm:grid-cols-2 gap-4"
  }, /*#__PURE__*/React.createElement(Field, {
    label: "Nombre"
  }, /*#__PURE__*/React.createElement("input", {
    value: form.nombre,
    onChange: onChange("nombre"),
    placeholder: "Ej: Camila",
    className: inputCls
  })), /*#__PURE__*/React.createElement(Field, {
    label: "Celular"
  }, /*#__PURE__*/React.createElement("input", {
    value: form.celular,
    onChange: onChange("celular"),
    placeholder: "9XX XXX XXX",
    className: inputCls
  })), /*#__PURE__*/React.createElement(Field, {
    label: "Tipo de producto que busca"
  }, /*#__PURE__*/React.createElement("input", {
    value: form.producto,
    onChange: onChange("producto"),
    placeholder: "Bloqueador, s\xE9rum, rutina...",
    className: inputCls
  })), /*#__PURE__*/React.createElement(Field, {
    label: "Tipo de piel (opcional)"
  }, /*#__PURE__*/React.createElement("div", {
    className: "relative"
  }, /*#__PURE__*/React.createElement("select", {
    value: form.piel,
    onChange: onChange("piel"),
    className: `${inputCls} appearance-none pr-9`
  }, tiposPiel.map(t => /*#__PURE__*/React.createElement("option", {
    key: t,
    value: t
  }, t || "Selecciona..."))), /*#__PURE__*/React.createElement(Icon, {
    name: "ChevronDown",
    className: "w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-[color:var(--muted-foreground)] pointer-events-none"
  }))), /*#__PURE__*/React.createElement("div", {
    className: "sm:col-span-2"
  }, /*#__PURE__*/React.createElement(Field, {
    label: "Mensaje"
  }, /*#__PURE__*/React.createElement("textarea", {
    rows: 4,
    value: form.mensaje,
    onChange: onChange("mensaje"),
    placeholder: "Cu\xE9ntanos qu\xE9 buscas o tu rutina actual...",
    className: `${inputCls} resize-none`
  })))), /*#__PURE__*/React.createElement("button", {
    type: "submit",
    className: "mt-7 w-full inline-flex items-center justify-center gap-2 rounded-full text-white font-medium text-[15px] py-4 transition hover:-translate-y-0.5 hover:shadow-[0_20px_40px_-15px_rgba(58,68,42,0.55)]",
    style: {
      background: "linear-gradient(135deg, var(--olive), var(--bronze))",
      boxShadow: "0 16px 36px -18px rgba(58,68,42,0.55)"
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "WhatsApp",
    className: "w-4 h-4"
  }), "Enviar por WhatsApp"), /*#__PURE__*/React.createElement("p", {
    className: "mt-3 text-[11.5px] text-[color:var(--muted-foreground)] text-center"
  }, "Al enviar abriremos WhatsApp con tu mensaje listo. No guardamos tus datos.")))));
}
const inputCls = "w-full rounded-2xl bg-[color:var(--soft-cream)] border border-[color:var(--border)] px-4 py-3 text-[14.5px] text-[color:var(--coffee)] placeholder:text-[color:var(--muted-foreground)]/70 outline-none focus:border-[color:var(--olive)] focus:bg-white transition";
function Field({
  label,
  children
}) {
  return /*#__PURE__*/React.createElement("label", {
    className: "block"
  }, /*#__PURE__*/React.createElement("span", {
    className: "text-[11px] uppercase tracking-[0.2em] text-[color:var(--muted-foreground)] mb-1.5 block"
  }, label), children);
}
function Footer({
  categories = BENI.categories
}) {
  const categoryLinks = categories.length ? categories.slice(0, 6).map(category => [category.title, "#productos"]) : [["Catálogo", "#productos"]];
  const cols = [{
    title: "Categorías",
    links: [...categoryLinks, ["Promociones", "#promociones"]]
  }, {
    title: "Atención",
    links: [["WhatsApp", BENI.whatsapp.general, true], ["Cómo comprar", "#como-comprar"], ["Preguntas frecuentes", "#faq"], ["Envíos a todo el Perú", "#como-comprar"], ["Entrega en tienda", "#como-comprar"], ["Contacto", "#contacto"]]
  }];
  return /*#__PURE__*/React.createElement("footer", {
    className: "relative text-[color:var(--cream)] overflow-hidden",
    style: {
      background: "linear-gradient(180deg, #2A3A20, #1d2b18)"
    }
  }, /*#__PURE__*/React.createElement(LeafSprig, {
    className: "absolute -left-4 top-12 w-44 text-[color:var(--rose-gold)]/15"
  }), /*#__PURE__*/React.createElement(LeafSprig, {
    className: "absolute right-2 bottom-2 w-52 text-[color:var(--rose-gold)]/10 rotate-180"
  }), /*#__PURE__*/React.createElement("div", {
    className: "max-w-7xl mx-auto px-5 md:px-8 py-16 md:py-20 grid md:grid-cols-12 gap-10 relative"
  }, /*#__PURE__*/React.createElement("div", {
    className: "md:col-span-5"
  }, /*#__PURE__*/React.createElement("div", {
    className: "flex items-center gap-3"
  }, /*#__PURE__*/React.createElement("div", {
    className: "w-14 h-14 rounded-2xl bg-[color:var(--soft-cream)] grid place-items-center"
  }, /*#__PURE__*/React.createElement("img", {
    src: "/store/assets/img/logo-beniglow.png",
    alt: "BeniGlow Store",
    className: "w-11 h-11 object-contain"
  })), /*#__PURE__*/React.createElement("div", {
    className: "leading-tight"
  }, /*#__PURE__*/React.createElement("p", {
    className: "font-serif text-2xl text-[color:var(--soft-cream)]"
  }, "BeniGlow Store"), /*#__PURE__*/React.createElement("p", {
    className: "text-[11px] uppercase tracking-[0.28em] text-[color:var(--rose-gold)]"
  }, "K-Beauty \xB7 Tacna"))), /*#__PURE__*/React.createElement("p", {
    className: "mt-6 text-[14.5px] text-[color:var(--cream)]/75 leading-relaxed max-w-md"
  }, "BeniGlow Store es una tienda de skincare y cosm\xE9tica coreana en Tacna, enfocada en productos originales, asesor\xEDa personalizada y env\xEDos a todo el Per\xFA."), /*#__PURE__*/React.createElement("div", {
    className: "mt-7 flex items-center gap-2.5"
  }, /*#__PURE__*/React.createElement("a", {
    href: BENI.brand.instagram,
    target: "_blank",
    rel: "noopener",
    className: "w-10 h-10 rounded-full grid place-items-center bg-white/8 hover:bg-[color:var(--rose-gold)] text-[color:var(--cream)] transition",
    "aria-label": "Instagram"
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "Instagram",
    className: "w-4 h-4"
  })), /*#__PURE__*/React.createElement("a", {
    href: BENI.brand.facebook,
    target: "_blank",
    rel: "noopener",
    className: "w-10 h-10 rounded-full grid place-items-center bg-white/8 hover:bg-[color:var(--rose-gold)] text-[color:var(--cream)] transition",
    "aria-label": "Facebook"
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "Facebook",
    className: "w-4 h-4"
  })), /*#__PURE__*/React.createElement("a", {
    href: BENI.whatsapp.general,
    target: "_blank",
    rel: "noopener",
    className: "w-10 h-10 rounded-full grid place-items-center bg-white/8 hover:bg-[color:var(--rose-gold)] text-[color:var(--cream)] transition",
    "aria-label": "WhatsApp"
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "WhatsApp",
    className: "w-4 h-4"
  })))), cols.map(col => /*#__PURE__*/React.createElement("div", {
    key: col.title,
    className: "md:col-span-3"
  }, /*#__PURE__*/React.createElement("p", {
    className: "text-[11px] uppercase tracking-[0.28em] text-[color:var(--rose-gold)] mb-4"
  }, col.title), /*#__PURE__*/React.createElement("ul", {
    className: "space-y-2.5"
  }, col.links.map(([label, href, ext]) => /*#__PURE__*/React.createElement("li", {
    key: label
  }, /*#__PURE__*/React.createElement("a", {
    href: href,
    target: ext ? "_blank" : undefined,
    rel: ext ? "noopener" : undefined,
    className: "text-[14px] text-[color:var(--cream)]/80 hover:text-[color:var(--rose-gold)] transition"
  }, label)))))), /*#__PURE__*/React.createElement("div", {
    className: "md:col-span-1 md:hidden lg:block"
  })), /*#__PURE__*/React.createElement("div", {
    className: "border-t border-white/8 relative"
  }, /*#__PURE__*/React.createElement("div", {
    className: "max-w-7xl mx-auto px-5 md:px-8 py-5 flex flex-col md:flex-row gap-3 items-start md:items-center justify-between text-[12.5px] text-[color:var(--cream)]/60"
  }, /*#__PURE__*/React.createElement("p", null, "\xA9 2026 BeniGlow Store. Todos los derechos reservados."), /*#__PURE__*/React.createElement("p", null, "Dise\xF1ado con cari\xF1o para tu rutina coreana \xB7  Tacna, Per\xFA"))));
}
function FloatingWhatsApp() {
  const [show, setShow] = useStateB(false);
  React.useEffect(() => {
    const t = setTimeout(() => setShow(true), 600);
    return () => clearTimeout(t);
  }, []);
  return /*#__PURE__*/React.createElement("a", {
    href: BENI.whatsapp.general,
    target: "_blank",
    rel: "noopener",
    className: `fixed z-[70] bottom-5 right-5 md:bottom-7 md:right-7 inline-flex items-center justify-center gap-2 w-14 h-14 p-0 md:w-auto md:h-auto md:pl-3 md:pr-5 md:py-3 rounded-full text-white font-medium shadow-[0_18px_40px_-12px_rgba(20,75,40,0.55)] transition-all duration-500 ${show ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"} hover:-translate-y-1`,
    style: {
      background: "linear-gradient(135deg, #25D366, #128C7E)"
    }
  }, /*#__PURE__*/React.createElement("span", {
    className: "relative w-10 h-10 grid place-items-center rounded-full bg-white/15"
  }, /*#__PURE__*/React.createElement("span", {
    className: "absolute inset-0 rounded-full bg-white/25 animate-ping-slow"
  }), /*#__PURE__*/React.createElement(Icon, {
    name: "WhatsApp",
    className: "w-5 h-5 relative z-10"
  })), /*#__PURE__*/React.createElement("span", {
    className: "hidden md:inline text-[14px]"
  }, "Comprar por WhatsApp"), /*#__PURE__*/React.createElement("span", {
    className: "sr-only md:hidden"
  }, "Comprar por WhatsApp"));
}
Object.assign(window, {
  Routines,
  Promos,
  Benefits,
  HowToBuy,
  CtaFinal,
  FaqSection,
  ContactSection,
  Footer,
  FloatingWhatsApp
});