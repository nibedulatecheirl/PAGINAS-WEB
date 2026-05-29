const { useState: useStateB, useMemo: useMemoB } = React;

function Routines() {
  const tones = {
    cream: {
      bg: "linear-gradient(160deg, #FFF5E8, #FBE8D4)",
      pill: "bg-[color:var(--bronze)]/15 text-[color:var(--coffee)]",
      icon: "text-[color:var(--bronze)]",
    },
    rose: {
      bg: "linear-gradient(160deg, #FCE7DC, #F1C7B0)",
      pill: "bg-[color:var(--rose-gold)]/20 text-[color:var(--coffee)]",
      icon: "text-[color:var(--rose-gold)]",
    },
    olive: {
      bg: "linear-gradient(160deg, #E8EFE0, #CFDCC2)",
      pill: "bg-[color:var(--olive)]/15 text-[color:var(--olive)]",
      icon: "text-[color:var(--olive)]",
    },
  };
  return (
    <section id="rutinas" className="section-band section-soft py-20 md:py-28">
      <div className="max-w-7xl mx-auto px-5 md:px-8">
        <Reveal>
          <SectionHeader
            eyebrow="Rutinas"
            title="Rutinas simples para empezar"
            description="Pasos básicos para construir tu rutina de día y de noche, según lo que tu piel necesita."
          />
        </Reveal>
        <div className="mt-12 md:mt-16 grid md:grid-cols-3 gap-5 md:gap-6">
          {BENI.routines.map((r, i) => {
            const t = tones[r.tone];
            return (
              <Reveal key={r.id} delay={i * 90}>
                <article
                  className="relative h-full p-7 md:p-8 rounded-[32px] overflow-hidden border border-[color:var(--border)]/70 group transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_36px_60px_-30px_rgba(58,68,42,0.35)]"
                  style={{ background: t.bg }}
                >
                  <LeafSprig className={`absolute -right-4 -top-4 w-28 ${t.icon} opacity-40`} />
                  <span className={`inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10.5px] font-semibold uppercase tracking-[0.18em] ${t.pill}`}>
                    Rutina {String(i + 1).padStart(2, "0")}
                  </span>
                  <h3 className="mt-5 font-serif text-2xl md:text-3xl text-[color:var(--coffee)] leading-tight">
                    {r.title}
                  </h3>
                  <p className="mt-2 text-sm text-[color:var(--coffee)]/75">{r.tagline}</p>

                  <ol className="mt-6 space-y-2.5 relative">
                    {r.steps.map((s, j) => (
                      <li key={s} className="flex items-center gap-3 bg-white/65 backdrop-blur rounded-2xl px-3.5 py-2.5 border border-white/60">
                        <span className={`w-7 h-7 rounded-full grid place-items-center text-[12px] font-semibold bg-white ${t.icon}`}>
                          {String(j + 1).padStart(2, "0")}
                        </span>
                        <span className="text-[14px] text-[color:var(--coffee)] font-medium">{s}</span>
                      </li>
                    ))}
                  </ol>

                  <div className="mt-7">
                    <WhatsAppButton label="Quiero esta rutina" message={r.message} size="sm" variant="solid" />
                  </div>
                </article>
              </Reveal>
            );
          })}
        </div>
      </div>
    </section>
  );
}

function Promos({ promos = BENI.promos, loading = false }) {
  return (
    <section id="promociones" className="section-band section-cream py-20 md:py-28 relative overflow-hidden">
      <div
        className="absolute inset-0 -z-10"
        style={{ background: "linear-gradient(180deg, var(--soft-cream), hsl(20 42% 96%) 52%, var(--cream))" }}
      />
      <div className="max-w-7xl mx-auto px-5 md:px-8">
        <Reveal>
          <SectionHeader
            eyebrow="Promociones"
            title="Promoción para cuidar tu piel"
            description="Campañas y combos seleccionados para empezar o reforzar tu rutina. Sujetos a stock disponible."
          />
        </Reveal>
        <div className="mt-12 md:mt-16 grid sm:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
          {loading ? [1, 2, 3, 4].map((i) => (
            <div key={i} className="h-64 rounded-[28px] bg-white/70 border border-[color:var(--border)] animate-pulse" />
          )) : null}
          {!loading && promos.length === 0 ? (
            <div className="sm:col-span-2 lg:col-span-3 rounded-3xl border border-dashed border-[color:var(--border)] bg-white/70 px-6 py-10 text-center">
              <p className="font-serif text-2xl text-[color:var(--coffee)]">Promociones en preparación</p>
              <p className="mt-2 text-sm text-[color:var(--muted-foreground)]">
                Pronto encontrarás aquí promociones y combos seleccionados para tu rutina.
              </p>
            </div>
          ) : null}
          {!loading && promos.map((p, i) => (
            <Reveal key={p.id} delay={i * 90}>
              <a
                href={BENI.whatsapp.link(`Hola, quiero consultar la promoción "${p.title}" de BeniGlow Store.`)}
                target="_blank"
                rel="noopener"
                className={`group block h-full rounded-[30px] bg-white border border-[color:var(--border)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_34px_70px_-32px_rgba(58,68,42,0.42)] relative overflow-hidden ${
                  promos.length === 1 ? "sm:col-span-2 lg:col-span-3 p-8 md:p-10" : "p-7"
                }`}
              >
                <div
                  className="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"
                  style={{ background: "radial-gradient(60% 55% at 85% 20%, hsl(20 38% 76% / .28), transparent 65%)" }}
                />
                <div className="relative flex items-center justify-between gap-3">
                  <Pill tone={p.badge === "Promo" ? "rose" : "olive"}>{p.badge}</Pill>
                  <Icon name="Gift" className="w-5 h-5 text-[color:var(--bronze)]" />
                </div>
                <h3 className={`relative mt-5 font-serif text-[color:var(--coffee)] leading-snug ${
                  promos.length === 1 ? "text-3xl md:text-5xl max-w-3xl" : "text-xl md:text-2xl"
                }`}>{p.title}</h3>
                <p className={`relative mt-3 text-[color:var(--muted-foreground)] leading-relaxed ${
                  promos.length === 1 ? "text-base max-w-2xl" : "text-sm"
                }`}>{p.description}</p>
                {p.highlight ? (
                  <div
                    className={`relative mt-6 rounded-3xl px-5 py-4 text-center border border-white/20 ${promos.length === 1 ? "md:max-w-sm" : ""}`}
                    style={{ background: "linear-gradient(135deg, var(--olive), #1f3520)" }}
                  >
                    <p className="text-[11px] uppercase tracking-[0.25em] text-white/75">Campaña</p>
                    <p className={`font-serif text-white ${promos.length === 1 ? "text-4xl" : "text-2xl"}`}>{p.highlight}</p>
                  </div>
                ) : null}
                <span className="relative mt-6 inline-flex items-center gap-1.5 text-sm font-medium text-[color:var(--olive)]">
                  Consultar por WhatsApp <Icon name="ArrowRight" className="w-4 h-4 transition-transform group-hover:translate-x-1" />
                </span>
              </a>
            </Reveal>
          ))}
        </div>
        <p className="mt-10 text-center text-[12.5px] text-[color:var(--muted-foreground)]">
          Promociones sujetas a stock y disponibilidad. Consulta por WhatsApp.
        </p>
      </div>
    </section>
  );
}

function Benefits() {
  return (
    <section className="section-band section-blush py-20 md:py-28">
      <div className="max-w-7xl mx-auto px-5 md:px-8">
        <Reveal>
          <SectionHeader
            eyebrow="Por qué nosotras"
            title="Por qué comprar en BeniGlow"
            description="Un servicio cercano y honesto: te ayudamos a elegir según tu piel, sin promesas exageradas."
          />
        </Reveal>
        <div className="mt-12 md:mt-16 grid sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
          {BENI.benefits.map((b, i) => (
            <Reveal key={b.title} delay={i * 50}>
              <div className="h-full p-6 rounded-3xl bg-white border border-[color:var(--border)] hover:border-[color:var(--olive)]/30 transition-colors">
                <span className="w-12 h-12 rounded-2xl grid place-items-center bg-gradient-to-br from-[color:var(--cream)] to-white text-[color:var(--olive)] mb-4 border border-[color:var(--border)]/60">
                  <Icon name={b.icon} className="w-5 h-5" />
                </span>
                <h4 className="font-serif text-[17px] text-[color:var(--coffee)]">{b.title}</h4>
                <p className="text-sm text-[color:var(--muted-foreground)] mt-1.5 leading-relaxed">{b.text}</p>
              </div>
            </Reveal>
          ))}
        </div>
      </div>
    </section>
  );
}

function HowToBuy() {
  return (
    <section id="como-comprar" className="section-band py-20 md:py-28 relative overflow-hidden">
      <div className="absolute inset-0 -z-10" style={{ background: "linear-gradient(180deg, var(--soft-cream), var(--background))" }} />
      <div className="max-w-7xl mx-auto px-5 md:px-8">
        <Reveal>
          <SectionHeader
            eyebrow="Proceso"
            title="Comprar es fácil"
            description="Pedidos directos por WhatsApp en 5 pasos simples."
          />
        </Reveal>

        <div className="mt-14 hidden md:block">
          <div className="relative">
            <div
              className="absolute top-9 left-[6%] right-[6%] h-px"
              style={{ background: "linear-gradient(90deg, transparent, var(--border), var(--border), transparent)" }}
            />
            <div className="grid grid-cols-5 gap-4">
              {BENI.steps.map((s, i) => (
                <Reveal key={s.n} delay={i * 100}>
                  <div className="text-center px-3">
                    <span
                      className="relative inline-grid place-items-center w-[72px] h-[72px] rounded-full font-serif text-xl text-[color:var(--olive)]"
                      style={{ background: "linear-gradient(135deg, #fff, var(--cream))", boxShadow: "0 12px 30px -16px rgba(58,68,42,0.35)", border: "1px solid var(--border)" }}
                    >
                      {s.n}
                      <span className="absolute -bottom-1 -right-1 w-3 h-3 rounded-full bg-[color:var(--rose-gold)]" />
                    </span>
                    <h4 className="font-serif text-lg text-[color:var(--coffee)] mt-5">{s.title}</h4>
                    <p className="text-sm text-[color:var(--muted-foreground)] mt-1.5 leading-relaxed">{s.text}</p>
                  </div>
                </Reveal>
              ))}
            </div>
          </div>
        </div>

        <div className="mt-12 md:hidden relative pl-6">
          <span className="absolute left-[14px] top-2 bottom-2 w-px bg-[color:var(--border)]" />
          {BENI.steps.map((s, i) => (
            <Reveal key={s.n} delay={i * 80}>
              <div className="relative pb-7">
                <span
                  className="absolute -left-[19px] top-0 w-9 h-9 rounded-full grid place-items-center font-serif text-sm text-[color:var(--olive)] bg-white border border-[color:var(--border)]"
                >
                  {s.n}
                </span>
                <div className="pl-7">
                  <h4 className="font-serif text-lg text-[color:var(--coffee)]">{s.title}</h4>
                  <p className="text-sm text-[color:var(--muted-foreground)] mt-1 leading-relaxed">{s.text}</p>
                </div>
              </div>
            </Reveal>
          ))}
        </div>
      </div>
    </section>
  );
}

function CtaFinal() {
  return (
    <section className="section-band section-soft py-20 md:py-28">
      <div className="max-w-6xl mx-auto px-5 md:px-8">
        <Reveal>
          <div
            className="relative overflow-hidden rounded-[40px] p-10 md:p-16 border border-[color:var(--border)]"
            style={{
              background:
                "radial-gradient(120% 90% at 100% 0%, hsl(20 38% 78% / .55), transparent 55%), radial-gradient(80% 80% at 0% 100%, hsl(95 28% 55% / .25), transparent 55%), linear-gradient(180deg, var(--soft-cream), var(--cream))",
            }}
          >
            <LeafSprig className="absolute -right-2 -top-4 w-40 text-[color:var(--olive)]/30" />
            <LeafSprig className="absolute -left-2 bottom-2 w-32 text-[color:var(--rose-gold)]/30 rotate-180" />

            <div className="relative max-w-3xl">
              <p className="text-[11px] uppercase tracking-[0.28em] text-[color:var(--bronze)] mb-4">Empieza hoy</p>
              <h2 className="font-serif text-[36px] md:text-[56px] leading-[1.02] text-[color:var(--coffee)] tracking-tight">
                ¿Lista para empezar tu rutina con{" "}
                <span
                  className="italic font-script-soft"
                  style={{
                    background: "linear-gradient(120deg, var(--rose-gold), var(--olive))",
                    WebkitBackgroundClip: "text",
                    backgroundClip: "text",
                    color: "transparent",
                  }}
                >
                  glow
                </span>
                ?
              </h2>
              <p className="mt-6 text-base md:text-lg text-[color:var(--muted-foreground)] max-w-xl leading-relaxed">
                Escríbenos y te ayudamos a elegir productos según tu piel, rutina y presupuesto.
              </p>
              <div className="mt-8 flex flex-wrap items-center gap-3">
                <WhatsAppButton label="Comprar por WhatsApp" size="lg" />
                <GradientButton href="#productos" variant="outline" size="lg">
                  Ver productos
                </GradientButton>
              </div>
            </div>
          </div>
        </Reveal>
      </div>
    </section>
  );
}

function FaqSection() {
  return (
    <section id="faq" className="section-band section-cream py-20 md:py-28">
      <div className="max-w-5xl mx-auto px-5 md:px-8">
        <Reveal>
          <SectionHeader
            eyebrow="Preguntas frecuentes"
            title="Resolvamos tus dudas"
            description="Si no encuentras lo que buscas, escríbenos por WhatsApp y te respondemos en cualquier momento."
          />
        </Reveal>
        <Reveal>
          <div className="mt-12">
            <FAQAccordion items={BENI.faq} />
          </div>
        </Reveal>
      </div>
    </section>
  );
}

function ContactSection() {
  const [form, setForm] = useStateB({
    nombre: "",
    celular: "",
    producto: "",
    piel: "",
    mensaje: "",
  });
  const onChange = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.value }));

  const message = useMemoB(() => {
    const lines = [
      "Hola BeniGlow Store, vengo de la web.",
      form.nombre && `Nombre: ${form.nombre}`,
      form.celular && `Celular: ${form.celular}`,
      form.producto && `Busco: ${form.producto}`,
      form.piel && `Tipo de piel: ${form.piel}`,
      form.mensaje && `Mensaje: ${form.mensaje}`,
    ].filter(Boolean);
    return lines.join("\n");
  }, [form]);

  const onSubmit = (e) => {
    e.preventDefault();
    window.open(BENI.whatsapp.link(message), "_blank", "noopener");
  };

  const tiposPiel = ["", "Grasa", "Mixta", "Seca", "Sensible", "No estoy segura"];

  return (
    <section id="contacto" className="section-band py-20 md:py-28 relative">
      <div className="absolute inset-0 -z-10" style={{ background: "linear-gradient(180deg, var(--background), var(--soft-cream))" }} />
      <div className="max-w-7xl mx-auto px-5 md:px-8 grid lg:grid-cols-[1fr_1.1fr] gap-12 lg:gap-16">
        <Reveal>
          <div>
            <p className="text-[11px] uppercase tracking-[0.28em] text-[color:var(--bronze)] mb-4">Contacto</p>
            <h2 className="font-serif text-3xl md:text-5xl text-[color:var(--coffee)] leading-[1.05] tracking-tight">
              Conversemos por WhatsApp
            </h2>
            <p className="mt-5 text-[color:var(--muted-foreground)] leading-relaxed max-w-md">
              Cuéntanos qué buscas, tu tipo de piel y cualquier duda. Te respondemos lo antes posible.
            </p>

            <ul className="mt-9 space-y-4">
              {[
                ["WhatsApp", "Icon:WhatsApp", BENI.whatsapp.number, BENI.whatsapp.general],
                ["Correo", "Mail", BENI.brand.email, `mailto:${BENI.brand.email}`],
                ["Ubicación", "MapPin", `${BENI.brand.city} · Envíos a todo el Perú`, null],
                ["Horario", "Clock", BENI.brand.hours, null],
              ].map(([label, icon, val, href]) => {
                const iconName = icon.replace("Icon:", "");
                const Inner = (
                  <>
                    <span className="w-11 h-11 rounded-2xl grid place-items-center bg-[color:var(--cream)] text-[color:var(--olive)] flex-none">
                      <Icon name={iconName} className="w-5 h-5" />
                    </span>
                    <div>
                      <p className="text-[11px] uppercase tracking-[0.2em] text-[color:var(--muted-foreground)]">{label}</p>
                      <p className="text-[15px] text-[color:var(--coffee)] font-medium">{val}</p>
                    </div>
                  </>
                );
                return (
                  <li key={label}>
                    {href ? (
                      <a href={href} target="_blank" rel="noopener" className="flex items-center gap-4 p-4 rounded-2xl bg-white/70 border border-[color:var(--border)] hover:bg-white transition">
                        {Inner}
                      </a>
                    ) : (
                      <div className="flex items-center gap-4 p-4 rounded-2xl bg-white/70 border border-[color:var(--border)]">
                        {Inner}
                      </div>
                    )}
                  </li>
                );
              })}
            </ul>

            <div className="mt-7 flex items-center gap-3">
              <a href={BENI.brand.instagram} target="_blank" rel="noopener" className="w-11 h-11 grid place-items-center rounded-full bg-white border border-[color:var(--border)] text-[color:var(--rose-gold)] hover:text-white hover:bg-[color:var(--rose-gold)] transition" aria-label="Instagram">
                <Icon name="Instagram" className="w-5 h-5" />
              </a>
              <a href={BENI.brand.facebook} target="_blank" rel="noopener" className="w-11 h-11 grid place-items-center rounded-full bg-white border border-[color:var(--border)] text-[color:var(--olive)] hover:text-white hover:bg-[color:var(--olive)] transition" aria-label="Facebook">
                <Icon name="Facebook" className="w-5 h-5" />
              </a>
            </div>
          </div>
        </Reveal>

        <Reveal delay={120}>
          <form onSubmit={onSubmit} className="rounded-[32px] bg-white border border-[color:var(--border)] p-7 md:p-10 shadow-[0_30px_60px_-40px_rgba(58,68,42,0.45)]">
            <p className="text-[11px] uppercase tracking-[0.25em] text-[color:var(--bronze)] mb-2">Envíanos un mensaje</p>
            <h3 className="font-serif text-2xl md:text-3xl text-[color:var(--coffee)]">Cuéntanos lo que buscas</h3>

            <div className="mt-7 grid sm:grid-cols-2 gap-4">
              <Field label="Nombre">
                <input value={form.nombre} onChange={onChange("nombre")} placeholder="Ej: Camila" className={inputCls} />
              </Field>
              <Field label="Celular">
                <input value={form.celular} onChange={onChange("celular")} placeholder="9XX XXX XXX" className={inputCls} />
              </Field>
              <Field label="Tipo de producto que busca">
                <input value={form.producto} onChange={onChange("producto")} placeholder="Bloqueador, sérum, rutina..." className={inputCls} />
              </Field>
              <Field label="Tipo de piel (opcional)">
                <div className="relative">
                  <select value={form.piel} onChange={onChange("piel")} className={`${inputCls} appearance-none pr-9`}>
                    {tiposPiel.map((t) => (
                      <option key={t} value={t}>{t || "Selecciona..."}</option>
                    ))}
                  </select>
                  <Icon name="ChevronDown" className="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-[color:var(--muted-foreground)] pointer-events-none" />
                </div>
              </Field>
              <div className="sm:col-span-2">
                <Field label="Mensaje">
                  <textarea
                    rows={4}
                    value={form.mensaje}
                    onChange={onChange("mensaje")}
                    placeholder="Cuéntanos qué buscas o tu rutina actual..."
                    className={`${inputCls} resize-none`}
                  />
                </Field>
              </div>
            </div>

            <button
              type="submit"
              className="mt-7 w-full inline-flex items-center justify-center gap-2 rounded-full text-white font-medium text-[15px] py-4 transition hover:-translate-y-0.5 hover:shadow-[0_20px_40px_-15px_rgba(58,68,42,0.55)]"
              style={{
                background: "linear-gradient(135deg, var(--olive), var(--bronze))",
                boxShadow: "0 16px 36px -18px rgba(58,68,42,0.55)",
              }}
            >
              <Icon name="WhatsApp" className="w-4 h-4" />
              Enviar por WhatsApp
            </button>
            <p className="mt-3 text-[11.5px] text-[color:var(--muted-foreground)] text-center">
              Al enviar abriremos WhatsApp con tu mensaje listo. No guardamos tus datos.
            </p>
          </form>
        </Reveal>
      </div>
    </section>
  );
}

const inputCls =
  "w-full rounded-2xl bg-[color:var(--soft-cream)] border border-[color:var(--border)] px-4 py-3 text-[14.5px] text-[color:var(--coffee)] placeholder:text-[color:var(--muted-foreground)]/70 outline-none focus:border-[color:var(--olive)] focus:bg-white transition";

function Field({ label, children }) {
  return (
    <label className="block">
      <span className="text-[11px] uppercase tracking-[0.2em] text-[color:var(--muted-foreground)] mb-1.5 block">{label}</span>
      {children}
    </label>
  );
}

function Footer({ categories = BENI.categories }) {
  const categoryLinks = categories.length
    ? categories.slice(0, 6).map((category) => [category.title, "#productos"])
    : [["Catálogo", "#productos"]];

  const cols = [
    {
      title: "Categorías",
      links: [...categoryLinks, ["Promociones", "#promociones"]],
    },
    {
      title: "Atención",
      links: [
        ["WhatsApp", BENI.whatsapp.general, true],
        ["Cómo comprar", "#como-comprar"],
        ["Preguntas frecuentes", "#faq"],
        ["Envíos a todo el Perú", "#como-comprar"],
        ["Entrega en tienda", "#como-comprar"],
        ["Contacto", "#contacto"],
      ],
    },
  ];
  return (
    <footer className="relative text-[color:var(--cream)] overflow-hidden" style={{ background: "linear-gradient(180deg, #2A3A20, #1d2b18)" }}>
      <LeafSprig className="absolute -left-4 top-12 w-44 text-[color:var(--rose-gold)]/15" />
      <LeafSprig className="absolute right-2 bottom-2 w-52 text-[color:var(--rose-gold)]/10 rotate-180" />

      <div className="max-w-7xl mx-auto px-5 md:px-8 py-16 md:py-20 grid md:grid-cols-12 gap-10 relative">
        <div className="md:col-span-5">
          <div className="flex items-center gap-3">
            <div className="w-14 h-14 rounded-2xl bg-[color:var(--soft-cream)] grid place-items-center">
              <img src="/store/assets/img/logo-beniglow.png" alt="BeniGlow Store" className="w-11 h-11 object-contain" />
            </div>
            <div className="leading-tight">
              <p className="font-serif text-2xl text-[color:var(--soft-cream)]">BeniGlow Store</p>
              <p className="text-[11px] uppercase tracking-[0.28em] text-[color:var(--rose-gold)]">K-Beauty · Tacna</p>
            </div>
          </div>
          <p className="mt-6 text-[14.5px] text-[color:var(--cream)]/75 leading-relaxed max-w-md">
            BeniGlow Store es una tienda de skincare y cosmética coreana en Tacna, enfocada
            en productos originales, asesoría personalizada y envíos a todo el Perú.
          </p>
          <div className="mt-7 flex items-center gap-2.5">
            <a href={BENI.brand.instagram} target="_blank" rel="noopener" className="w-10 h-10 rounded-full grid place-items-center bg-white/8 hover:bg-[color:var(--rose-gold)] text-[color:var(--cream)] transition" aria-label="Instagram">
              <Icon name="Instagram" className="w-4 h-4" />
            </a>
            <a href={BENI.brand.facebook} target="_blank" rel="noopener" className="w-10 h-10 rounded-full grid place-items-center bg-white/8 hover:bg-[color:var(--rose-gold)] text-[color:var(--cream)] transition" aria-label="Facebook">
              <Icon name="Facebook" className="w-4 h-4" />
            </a>
            <a href={BENI.whatsapp.general} target="_blank" rel="noopener" className="w-10 h-10 rounded-full grid place-items-center bg-white/8 hover:bg-[color:var(--rose-gold)] text-[color:var(--cream)] transition" aria-label="WhatsApp">
              <Icon name="WhatsApp" className="w-4 h-4" />
            </a>
          </div>
        </div>

        {cols.map((col) => (
          <div key={col.title} className="md:col-span-3">
            <p className="text-[11px] uppercase tracking-[0.28em] text-[color:var(--rose-gold)] mb-4">{col.title}</p>
            <ul className="space-y-2.5">
              {col.links.map(([label, href, ext]) => (
                <li key={label}>
                  <a
                    href={href}
                    target={ext ? "_blank" : undefined}
                    rel={ext ? "noopener" : undefined}
                    className="text-[14px] text-[color:var(--cream)]/80 hover:text-[color:var(--rose-gold)] transition"
                  >
                    {label}
                  </a>
                </li>
              ))}
            </ul>
          </div>
        ))}

        <div className="md:col-span-1 md:hidden lg:block" />
      </div>

      <div className="border-t border-white/8 relative">
        <div className="max-w-7xl mx-auto px-5 md:px-8 py-5 flex flex-col md:flex-row gap-3 items-start md:items-center justify-between text-[12.5px] text-[color:var(--cream)]/60">
          <p>© 2026 BeniGlow Store. Todos los derechos reservados.</p>
          <p>Diseñado con cariño para tu rutina coreana ·  Tacna, Perú</p>
        </div>
      </div>
    </footer>
  );
}

function FloatingWhatsApp() {
  const [show, setShow] = useStateB(false);
  React.useEffect(() => {
    const t = setTimeout(() => setShow(true), 600);
    return () => clearTimeout(t);
  }, []);
  return (
    <a
      href={BENI.whatsapp.general}
      target="_blank"
      rel="noopener"
      className={`fixed z-[70] bottom-5 right-5 md:bottom-7 md:right-7 inline-flex items-center justify-center gap-2 w-14 h-14 p-0 md:w-auto md:h-auto md:pl-3 md:pr-5 md:py-3 rounded-full text-white font-medium shadow-[0_18px_40px_-12px_rgba(20,75,40,0.55)] transition-all duration-500 ${
        show ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"
      } hover:-translate-y-1`}
      style={{ background: "linear-gradient(135deg, #25D366, #128C7E)" }}
    >
      <span className="relative w-10 h-10 grid place-items-center rounded-full bg-white/15">
        <span className="absolute inset-0 rounded-full bg-white/25 animate-ping-slow" />
        <Icon name="WhatsApp" className="w-5 h-5 relative z-10" />
      </span>
      <span className="hidden md:inline text-[14px]">Comprar por WhatsApp</span>
      <span className="sr-only md:hidden">Comprar por WhatsApp</span>
    </a>
  );
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
  FloatingWhatsApp,
});
