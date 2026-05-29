const { useState, useEffect, useRef, createContext, useContext } = React;

const I = {
  Sun: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <circle cx="12" cy="12" r="4" />
      <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
    </svg>
  ),
  Droplets: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M7 16.5A4.5 4.5 0 1 0 11.5 12c-1.5-1.5-2-3.5-2-5.5 0 0-5 4.5-5 8.5A2.5 2.5 0 0 0 7 16.5z" />
      <path d="M15 17a5 5 0 1 0 5-5c-1.5-1.5-2-3.5-2-5.5 0 0-5 4.5-5 8.5" />
    </svg>
  ),
  Sparkles: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M12 3l1.6 4.4L18 9l-4.4 1.6L12 15l-1.6-4.4L6 9l4.4-1.6L12 3z" />
      <path d="M19 14l.8 2.2L22 17l-2.2.8L19 20l-.8-2.2L16 17l2.2-.8L19 14zM5 15l.6 1.5L7 17l-1.4.5L5 19l-.6-1.5L3 17l1.4-.5L5 15z" />
    </svg>
  ),
  FlaskConical: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M9 3h6M10 3v6L4.5 18.5A2 2 0 0 0 6.2 21.5h11.6a2 2 0 0 0 1.7-3L14 9V3" />
      <path d="M7 15h10" />
    </svg>
  ),
  Cloud: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M17 18H7a4 4 0 1 1 .7-7.94A6 6 0 0 1 19 12a4 4 0 0 1-2 6z" />
    </svg>
  ),
  Flower2: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M12 5a3 3 0 1 1 3 3 3 3 0 1 1-3 3 3 3 0 1 1-3-3 3 3 0 1 1 3-3z" />
      <path d="M12 14v7M9 19h6" />
    </svg>
  ),
  CircleDashed: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M10.1 2.2a10 10 0 0 1 3.8 0M16.8 3.5a10 10 0 0 1 2.7 2.7M21.8 10.1a10 10 0 0 1 0 3.8M20.5 16.8a10 10 0 0 1-2.7 2.7M13.9 21.8a10 10 0 0 1-3.8 0M7.2 20.5a10 10 0 0 1-2.7-2.7M2.2 13.9a10 10 0 0 1 0-3.8M3.5 7.2a10 10 0 0 1 2.7-2.7" />
    </svg>
  ),
  Leaf: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M11 20a8 8 0 0 0 8-8V4h-7a8 8 0 0 0-8 8 4 4 0 0 0 4 4" />
      <path d="M3 21c0-7 6-12 14-13" />
    </svg>
  ),
  Gift: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M20 12v9H4v-9M22 7H2v5h20V7zM12 22V7M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" />
    </svg>
  ),
  ShieldCheck: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
      <path d="m9 12 2 2 4-4" />
    </svg>
  ),
  Plane: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z" />
    </svg>
  ),
  MessageCircle: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8v.5z" />
    </svg>
  ),
  Truck: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M14 18V6H1v12h2M14 8h5l3 4v6h-2" />
      <circle cx="6" cy="18" r="2" />
      <circle cx="18" cy="18" r="2" />
    </svg>
  ),
  Store: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M3 9V21H21V9M2 9l2-5h16l2 5M8 9v0a3 3 0 0 0 6 0M14 9v0a3 3 0 0 0 6 0M2 9a3 3 0 0 0 6 0M9 21v-6h6v6" />
    </svg>
  ),
  Clock: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <circle cx="12" cy="12" r="9" />
      <path d="M12 7v5l3 2" />
    </svg>
  ),
  Wallet: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M21 12V8a2 2 0 0 0-2-2H5a2 2 0 0 1 0-4h13v4" />
      <path d="M3 6v12a2 2 0 0 0 2 2h15v-4" />
      <path d="M18 12a2 2 0 0 0 0 4h3v-4z" />
    </svg>
  ),
  ShoppingBag: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M6 8h12l-1 13H7L6 8z" />
      <path d="M9 8a3 3 0 0 1 6 0" />
    </svg>
  ),
  Plus: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.9" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M12 5v14M5 12h14" />
    </svg>
  ),
  Minus: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.9" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M5 12h14" />
    </svg>
  ),
  Trash: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M3 6h18M8 6V4h8v2M6 6l1 15h10l1-15" />
      <path d="M10 11v6M14 11v6" />
    </svg>
  ),
  WhatsApp: (p) => (
    <svg viewBox="0 0 24 24" fill="currentColor" {...p}>
      <path d="M.057 24l1.687-6.163a11.867 11.867 0 0 1-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.821 11.821 0 0 1 8.413 3.488 11.824 11.824 0 0 1 3.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 0 1-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 0 0 1.523 5.295l.36.57-1.013 3.7 3.799-.999zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.711.307 1.265.49 1.697.628.713.226 1.362.194 1.875.118.572-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
    </svg>
  ),
  ArrowRight: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M5 12h14M13 5l7 7-7 7" />
    </svg>
  ),
  Menu: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M3 6h18M3 12h18M3 18h18" />
    </svg>
  ),
  X: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M18 6 6 18M6 6l12 12" />
    </svg>
  ),
  ChevronDown: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="m6 9 6 6 6-6" />
    </svg>
  ),
  Mail: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <rect width="20" height="16" x="2" y="4" rx="2" />
      <path d="m22 7-10 6L2 7" />
    </svg>
  ),
  MapPin: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z" />
      <circle cx="12" cy="10" r="3" />
    </svg>
  ),
  Instagram: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <rect width="20" height="20" x="2" y="2" rx="5" />
      <path d="M16 11.4A4 4 0 1 1 12.6 8 4 4 0 0 1 16 11.4z" />
      <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" />
    </svg>
  ),
  Facebook: (p) => (
    <svg viewBox="0 0 24 24" fill="currentColor" {...p}>
      <path d="M22 12a10 10 0 1 0-11.6 9.9v-7H8v-3h2.4V9.5c0-2.4 1.4-3.7 3.6-3.7 1 0 2.1.2 2.1.2v2.3h-1.2c-1.2 0-1.5.7-1.5 1.5V12h2.6l-.4 3h-2.2v7A10 10 0 0 0 22 12z" />
    </svg>
  ),
  Check: (p) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" {...p}>
      <path d="m20 6-11 11L4 12" />
    </svg>
  ),
};

function Icon({ name, className = "w-5 h-5", ...rest }) {
  const Cmp = I[name] || I.Sparkles;
  return <Cmp className={className} {...rest} />;
}

function LeafSprig({ className = "", style }) {
  return (
    <svg viewBox="0 0 120 160" className={className} style={style} fill="none" aria-hidden="true">
      <path
        d="M60 150 C 58 90, 30 50, 18 22"
        stroke="currentColor"
        strokeWidth="1.2"
        strokeLinecap="round"
        opacity="0.55"
      />
      {[0, 1, 2, 3, 4, 5].map((i) => {
        const t = i / 5;
        const cx = 60 - t * 38 - (1 - t) * 0;
        const cy = 150 - t * 130;
        const rot = -25 - i * 6;
        return (
          <g key={i} transform={`translate(${cx} ${cy}) rotate(${rot})`}>
            <path
              d="M0 0 C 8 -6, 18 -6, 24 -2 C 18 4, 8 4, 0 0 Z"
              fill="currentColor"
              opacity="0.55"
            />
          </g>
        );
      })}
    </svg>
  );
}

function GradientButton({
  as = "a",
  href,
  onClick,
  children,
  variant = "solid",
  size = "md",
  icon,
  className = "",
  ...rest
}) {
  const sizes = {
    sm: "text-xs px-4 py-2",
    md: "text-sm px-5 py-3",
    lg: "text-base px-7 py-3.5",
  };
  const base = `inline-flex items-center justify-center gap-2 rounded-full font-medium tracking-wide transition-all duration-300 will-change-transform ${sizes[size]} ${className}`;

  if (variant === "solid") {
    const cls = `${base} text-white shadow-[0_10px_30px_-12px_rgba(58,68,42,0.5)] hover:shadow-[0_18px_40px_-12px_rgba(58,68,42,0.55)] hover:-translate-y-0.5`;
    const style = {
      background:
        "linear-gradient(135deg, var(--olive) 0%, var(--olive-soft) 55%, var(--bronze) 100%)",
    };
    const Tag = as;
    return (
      <Tag href={href} onClick={onClick} className={cls} style={style} {...rest}>
        {children}
        {icon}
      </Tag>
    );
  }

  if (variant === "outline") {
    const Tag = as;
    return (
      <Tag
        href={href}
        onClick={onClick}
        className={`${base} relative overflow-hidden text-[color:var(--coffee)] hover:text-[color:var(--olive)]`}
        {...rest}
      >
        <span
          aria-hidden="true"
          className="absolute inset-0 rounded-full p-[1.5px]"
          style={{
            background:
              "linear-gradient(135deg, var(--rose-gold), var(--olive) 60%, var(--bronze))",
          }}
        >
          <span
            className="block w-full h-full rounded-full"
            style={{ background: "var(--soft-cream)" }}
          />
        </span>
        <span className="relative z-10 inline-flex items-center gap-2">
          {children}
          {icon}
        </span>
      </Tag>
    );
  }

  const Tag = as;
  return (
    <Tag
      href={href}
      onClick={onClick}
      className={`${base} text-[color:var(--coffee)] hover:text-[color:var(--olive)] hover:bg-[color:var(--muted)]`}
      {...rest}
    >
      {children}
      {icon}
    </Tag>
  );
}

function WhatsAppButton({ label = "Comprar por WhatsApp", message, variant = "solid", size = "md", className = "", showIcon = true }) {
  const href = message ? BENI.whatsapp.link(message) : BENI.whatsapp.general;
  return (
    <GradientButton
      href={href}
      target="_blank"
      rel="noopener"
      variant={variant}
      size={size}
      className={className}
      icon={showIcon ? <Icon name="WhatsApp" className="w-4 h-4" /> : null}
    >
      {label}
    </GradientButton>
  );
}

function SectionHeader({ eyebrow, title, description, align = "center", className = "" }) {
  return (
    <div className={`${align === "center" ? "text-center mx-auto" : ""} max-w-3xl ${className}`}>
      {eyebrow ? (
        <div
          className={`inline-flex items-center gap-2 text-[11px] uppercase tracking-[0.25em] text-[color:var(--bronze)] mb-4 ${
            align === "center" ? "justify-center" : ""
          }`}
        >
          <span className="block w-6 h-px bg-[color:var(--bronze)]/60" />
          {eyebrow}
          <span className="block w-6 h-px bg-[color:var(--bronze)]/60" />
        </div>
      ) : null}
      <h2 className="font-serif text-3xl md:text-5xl leading-[1.05] text-[color:var(--coffee)] tracking-tight">
        {title}
      </h2>
      {description ? (
        <p className="mt-5 text-base md:text-lg text-[color:var(--muted-foreground)] max-w-2xl mx-auto leading-relaxed">
          {description}
        </p>
      ) : null}
    </div>
  );
}

function Pill({ children, tone = "olive", className = "" }) {
  const tones = {
    olive: "bg-[color:var(--olive)]/95 text-white",
    cream: "bg-[color:var(--cream)] text-[color:var(--coffee)] border border-[color:var(--border)]",
    rose: "bg-[color:var(--rose-gold)]/95 text-white",
    muted: "bg-white/80 text-[color:var(--coffee)] border border-[color:var(--border)] backdrop-blur",
  };
  return (
    <span className={`inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.15em] ${tones[tone]} ${className}`}>
      {children}
    </span>
  );
}

function FAQAccordion({ items }) {
  const [open, setOpen] = useState(0);
  return (
    <div className="divide-y divide-[color:var(--border)] rounded-3xl border border-[color:var(--border)] bg-white/70 backdrop-blur-sm">
      {items.map((item, i) => {
        const isOpen = open === i;
        return (
          <div key={i} className="px-6 md:px-8">
            <button
              onClick={() => setOpen(isOpen ? -1 : i)}
              className="w-full flex items-center justify-between gap-6 py-5 md:py-6 text-left"
              aria-expanded={isOpen}
            >
              <span className="font-serif text-lg md:text-xl text-[color:var(--coffee)] leading-snug">
                {item.q}
              </span>
              <span
                className={`flex-none w-9 h-9 rounded-full grid place-items-center border border-[color:var(--border)] text-[color:var(--olive)] transition-transform duration-300 ${
                  isOpen ? "rotate-180 bg-[color:var(--olive)]/8" : ""
                }`}
              >
                <Icon name="ChevronDown" className="w-4 h-4" />
              </span>
            </button>
            <div
              className="grid transition-all duration-400 ease-out"
              style={{
                gridTemplateRows: isOpen ? "1fr" : "0fr",
                opacity: isOpen ? 1 : 0,
              }}
            >
              <div className="overflow-hidden">
                <p className="pb-6 md:pb-7 pr-12 text-[15px] text-[color:var(--muted-foreground)] leading-relaxed">
                  {item.a}
                </p>
              </div>
            </div>
          </div>
        );
      })}
    </div>
  );
}

function ProductImage({ product }) {
  const [loaded, setLoaded] = useState(false);

  return (
    <>
      <div
        className={`absolute inset-0 bg-gradient-to-br from-[color:var(--cream)] via-white to-[color:var(--soft-cream)] transition-opacity duration-500 ${
          loaded ? "opacity-0" : "opacity-100"
        }`}
      />
      <img
        src={product.image}
        alt={`${product.brand} ${product.name}`}
        className={`absolute inset-0 w-full h-full object-cover transition-[opacity,transform] duration-[900ms] ease-out group-hover:scale-105 ${
          loaded ? "opacity-100 scale-100" : "opacity-0 scale-[1.015]"
        }`}
        loading="lazy"
        decoding="async"
        onLoad={() => setLoaded(true)}
      />
    </>
  );
}

function ProductCard({ product, onDetail, onAddToCart }) {
  const disabled = !product.available;

  return (
    <article className="group relative flex flex-col bg-white rounded-[28px] border border-[color:var(--border)] overflow-hidden transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_30px_60px_-30px_rgba(58,68,42,0.35)]">
      <div className="relative aspect-[4/5] overflow-hidden bg-gradient-to-b from-[color:var(--cream)] to-[color:var(--soft-cream)]">
        <ProductImage product={product} />
        <div className="absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-white/40 to-transparent pointer-events-none" />
        <div className="absolute top-4 left-4">
          <Pill tone={product.badge === "Stock" ? "olive" : "muted"}>{product.badge}</Pill>
        </div>
        <div className="absolute bottom-4 right-4">
          <span className="inline-flex items-center text-[10px] uppercase tracking-[0.25em] text-[color:var(--bronze)] bg-white/85 backdrop-blur px-2.5 py-1 rounded-full border border-[color:var(--border)]">
            {product.category}
          </span>
        </div>
      </div>

      <div className="flex-1 flex flex-col p-5 md:p-6 gap-3">
        <div className="flex items-baseline justify-between gap-3">
          <p className="text-[10px] uppercase tracking-[0.28em] text-[color:var(--bronze)] font-semibold">
            {product.brand}
          </p>
          <p className="font-serif text-lg text-[color:var(--olive)] tabular-nums">{product.price}</p>
        </div>
        <h3 className="font-serif text-xl text-[color:var(--coffee)] leading-snug">
          {product.name}
        </h3>
        <ul className="space-y-1.5 mt-1">
          {product.benefits.map((b) => (
            <li key={b} className="flex items-center gap-2 text-[13px] text-[color:var(--muted-foreground)]">
              <span className="w-1 h-1 rounded-full bg-[color:var(--rose-gold)]" />
              {b}
            </li>
          ))}
        </ul>

        <div className="mt-auto pt-5 flex items-center gap-2">
          <button
            type="button"
            onClick={() => onAddToCart(product)}
            disabled={disabled}
            className={`flex-1 inline-flex items-center justify-center gap-2 rounded-full text-sm px-4 py-2 font-medium transition ${
              disabled
                ? "bg-[color:var(--muted)] text-[color:var(--muted-foreground)] cursor-not-allowed"
                : "text-white hover:-translate-y-0.5"
            }`}
            style={disabled ? undefined : { background: "linear-gradient(135deg, var(--olive), var(--bronze))" }}
          >
            <Icon name="ShoppingBag" className="w-4 h-4" />
            Agregar
          </button>
          <a
            href={BENI.whatsapp.link(product.whatsappMessage)}
            target="_blank"
            rel="noopener"
            className="w-10 h-10 rounded-full grid place-items-center border border-[color:var(--border)] text-[color:var(--olive)] hover:bg-[color:var(--cream)] transition"
            aria-label="Pedir por WhatsApp"
          >
            <Icon name="WhatsApp" className="w-4 h-4" />
          </a>
          <button
            onClick={() => onDetail(product)}
            className="text-sm font-medium text-[color:var(--coffee)] hover:text-[color:var(--olive)] underline-offset-4 hover:underline px-3 py-2"
          >
            Ver detalle
          </button>
        </div>
      </div>
    </article>
  );
}

function CategoryCard({ icon, title, description, message }) {
  return (
    <a
      href={BENI.whatsapp.link(message)}
      target="_blank"
      rel="noopener"
      className="group relative flex flex-col items-start gap-4 p-6 md:p-7 rounded-3xl bg-white border border-[color:var(--border)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_24px_50px_-30px_rgba(58,68,42,0.4)]"
    >
      <span
        className="w-14 h-14 rounded-2xl grid place-items-center text-[color:var(--olive)] transition-colors duration-300 group-hover:text-white"
        style={{ background: "linear-gradient(135deg, var(--cream), #fff)" }}
      >
        <span
          aria-hidden="true"
          className="absolute opacity-0 group-hover:opacity-100 w-14 h-14 rounded-2xl transition-opacity"
          style={{ background: "linear-gradient(135deg, var(--olive), var(--bronze))" }}
        />
        <Icon name={icon} className="w-7 h-7 relative z-10" />
      </span>
      <div className="flex-1">
        <h3 className="font-serif text-xl text-[color:var(--coffee)]">{title}</h3>
        <p className="mt-1.5 text-sm text-[color:var(--muted-foreground)] leading-relaxed">
          {description}
        </p>
      </div>
      <span className="inline-flex items-center gap-1.5 text-sm font-medium text-[color:var(--olive)]">
        Consultar <Icon name="ArrowRight" className="w-4 h-4 transition-transform group-hover:translate-x-1" />
      </span>
    </a>
  );
}

function ProductModal({ product, onClose, onAddToCart }) {
  useEffect(() => {
    if (!product) return;
    const prev = document.body.style.overflow;
    document.body.style.overflow = "hidden";
    const onKey = (e) => e.key === "Escape" && onClose();
    window.addEventListener("keydown", onKey);
    return () => {
      document.body.style.overflow = prev;
      window.removeEventListener("keydown", onKey);
    };
  }, [product]);

  if (!product) return null;
  return (
    <div className="fixed inset-0 z-[80] flex items-end md:items-center justify-center p-0 md:p-6">
      <div
        className="absolute inset-0 bg-[color:var(--coffee)]/30 backdrop-blur-sm"
        onClick={onClose}
      />
      <div className="relative w-full md:max-w-4xl bg-[color:var(--soft-cream)] rounded-t-[32px] md:rounded-[32px] overflow-hidden shadow-2xl">
        <button
          onClick={onClose}
          className="absolute top-4 right-4 z-10 w-10 h-10 grid place-items-center rounded-full bg-white/90 backdrop-blur text-[color:var(--coffee)] border border-[color:var(--border)] hover:bg-white"
          aria-label="Cerrar"
        >
          <Icon name="X" className="w-5 h-5" />
        </button>
        <div className="grid md:grid-cols-2">
          <div className="relative aspect-square md:aspect-auto bg-[color:var(--cream)]">
            <img
              src={product.image}
              alt={product.name}
              className="absolute inset-0 w-full h-full object-cover"
            />
          </div>
          <div className="p-7 md:p-10 flex flex-col gap-5">
            <div className="flex items-center justify-between gap-4">
              <span className="text-[10px] uppercase tracking-[0.28em] text-[color:var(--bronze)] font-semibold">
                {product.brand}
              </span>
              <Pill tone={product.badge === "Stock" ? "olive" : "muted"}>{product.badge}</Pill>
            </div>
            <h3 className="font-serif text-3xl md:text-4xl text-[color:var(--coffee)] leading-tight">
              {product.name}
            </h3>
            <p className="text-sm text-[color:var(--muted-foreground)]">
              {product.category} · Apoya tu rutina diaria con una fórmula coreana
              de textura ligera y acabado natural.
            </p>
            <ul className="space-y-2">
              {product.benefits.map((b) => (
                <li key={b} className="flex items-center gap-2.5 text-sm text-[color:var(--coffee)]">
                  <Icon name="Check" className="w-4 h-4 text-[color:var(--olive)]" />
                  {b}
                </li>
              ))}
            </ul>
            <div className="mt-2 flex items-end justify-between gap-4 pt-2 border-t border-[color:var(--border)]">
              <div>
                <p className="text-xs uppercase tracking-[0.2em] text-[color:var(--muted-foreground)]">
                  Precio
                </p>
                <p className="font-serif text-3xl text-[color:var(--olive)]">{product.price}</p>
              </div>
              <div className="flex flex-wrap justify-end gap-2">
                <button
                  type="button"
                  onClick={() => onAddToCart(product)}
                  disabled={!product.available}
                  className={`inline-flex items-center justify-center gap-2 rounded-full text-sm px-5 py-3 font-medium transition ${
                    product.available
                      ? "text-white hover:-translate-y-0.5"
                      : "bg-[color:var(--muted)] text-[color:var(--muted-foreground)] cursor-not-allowed"
                  }`}
                  style={product.available ? { background: "linear-gradient(135deg, var(--olive), var(--bronze))" } : undefined}
                >
                  <Icon name="ShoppingBag" className="w-4 h-4" />
                  Agregar
                </button>
                <WhatsAppButton
                  label="WhatsApp"
                  message={product.whatsappMessage}
                  size="md"
                  variant="outline"
                />
              </div>
            </div>
            <p className="text-[11px] text-[color:var(--muted-foreground)] leading-relaxed">
              * Recomendamos como apoyo a tu rutina diaria. Si tienes piel sensible
              o alguna condición dermatológica, consulta con un especialista.
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}

function CartDrawer({ open, cart, onClose, onQuantity, onRemove, onClear }) {
  const [form, setForm] = useState({
    nombres: "",
    apellidos: "",
    telefono: "",
    email: "",
    direccion: "",
    ciudad: "Tacna",
    referencia: "",
    metodo_pago: "por_coordinar",
    notas: "",
  });
  const [status, setStatus] = useState({ state: "idle", message: "", order: null });
  const money = React.useMemo(
    () => new Intl.NumberFormat("es-PE", { style: "currency", currency: "PEN" }),
    []
  );

  useEffect(() => {
    if (!open) return;
    const prev = document.body.style.overflow;
    document.body.style.overflow = "hidden";
    const onKey = (e) => e.key === "Escape" && onClose();
    window.addEventListener("keydown", onKey);
    return () => {
      document.body.style.overflow = prev;
      window.removeEventListener("keydown", onKey);
    };
  }, [open]);

  const total = cart.reduce((sum, item) => {
    const unit = Number(item.product.raw?.precio_final ?? item.product.raw?.precio ?? 0);
    return sum + unit * item.quantity;
  }, 0);

  const onChange = (key) => (event) => {
    setForm((current) => ({ ...current, [key]: event.target.value }));
  };

  const submitOrder = async (event) => {
    event.preventDefault();

    if (!cart.length) return;

    setStatus({ state: "loading", message: "Registrando pedido...", order: null });

    try {
      const payload = {
        cliente: {
          nombres: form.nombres,
          apellidos: form.apellidos,
          email: form.email,
          telefono: form.telefono,
        },
        direccion_envio: {
          direccion: form.direccion,
          ciudad: form.ciudad,
          referencia: form.referencia,
        },
        items: cart.map((item) => ({
          producto_id: item.product.sourceId,
          cantidad: item.quantity,
        })),
        metodo_pago: form.metodo_pago,
        origen: "beniglow-store-web",
        notas: form.notas,
      };

      const response = await BENI.api.createOrder(payload);
      setStatus({
        state: "success",
        message: "Pedido registrado correctamente.",
        order: response.data,
      });
      onClear();
    } catch (error) {
      setStatus({
        state: "error",
        message: "No se pudo registrar el pedido. Revisa stock o intenta nuevamente.",
        order: null,
      });
    }
  };

  if (!open) return null;

  return (
    <div className="fixed inset-0 z-[90]">
      <div className="absolute inset-0 bg-[color:var(--coffee)]/35 backdrop-blur-sm" onClick={onClose} />
      <aside className="absolute right-0 top-0 h-full w-full max-w-xl bg-[color:var(--soft-cream)] shadow-2xl flex flex-col">
        <div className="px-5 md:px-7 py-5 border-b border-[color:var(--border)] flex items-center justify-between gap-4">
          <div>
            <p className="text-[11px] uppercase tracking-[0.24em] text-[color:var(--bronze)]">Pedido web</p>
            <h2 className="font-serif text-3xl text-[color:var(--coffee)]">Tu pedido BeniGlow</h2>
          </div>
          <button onClick={onClose} className="w-10 h-10 rounded-full grid place-items-center border border-[color:var(--border)] bg-white" aria-label="Cerrar pedido">
            <Icon name="X" className="w-5 h-5" />
          </button>
        </div>

        <div className="flex-1 overflow-y-auto px-5 md:px-7 py-5">
          {status.state === "success" ? (
            <div className="rounded-3xl border border-[color:var(--border)] bg-white p-6">
              <Pill tone="olive">Pedido registrado</Pill>
              <h3 className="mt-4 font-serif text-3xl text-[color:var(--coffee)]">
                {status.order?.codigo}
              </h3>
              <p className="mt-3 text-sm text-[color:var(--muted-foreground)] leading-relaxed">
                Recibimos tu pedido. Coordinaremos pago, entrega y confirmación de stock.
              </p>
              <a
                href={BENI.whatsapp.link(`Hola, registre mi pedido web ${status.order?.codigo} en BeniGlow Store.`)}
                target="_blank"
                rel="noopener"
                className="mt-6 inline-flex items-center justify-center gap-2 rounded-full px-5 py-3 text-white font-medium"
                style={{ background: "linear-gradient(135deg, #25D366, #128C7E)" }}
              >
                <Icon name="WhatsApp" className="w-4 h-4" />
                Coordinar por WhatsApp
              </a>
            </div>
          ) : (
            <>
              <div className="space-y-3">
                {cart.length ? cart.map((item) => (
                  <div key={item.product.sourceId} className="flex gap-3 rounded-3xl border border-[color:var(--border)] bg-white p-3">
                    <img src={item.product.image} alt={item.product.name} className="w-20 h-24 rounded-2xl object-cover bg-[color:var(--cream)]" />
                    <div className="min-w-0 flex-1">
                      <p className="text-[10px] uppercase tracking-[0.22em] text-[color:var(--bronze)]">{item.product.brand}</p>
                      <h3 className="font-serif text-lg text-[color:var(--coffee)] leading-tight">{item.product.name}</h3>
                      <p className="mt-1 text-sm text-[color:var(--olive)]">{item.product.price}</p>
                      <div className="mt-3 flex items-center justify-between gap-2">
                        <div className="inline-flex items-center rounded-full border border-[color:var(--border)] overflow-hidden">
                          <button type="button" onClick={() => onQuantity(item.product.sourceId, item.quantity - 1)} className="w-9 h-9 grid place-items-center bg-[color:var(--cream)]">
                            <Icon name="Minus" className="w-4 h-4" />
                          </button>
                          <span className="w-10 text-center text-sm font-medium">{item.quantity}</span>
                          <button type="button" onClick={() => onQuantity(item.product.sourceId, item.quantity + 1)} className="w-9 h-9 grid place-items-center bg-[color:var(--cream)]">
                            <Icon name="Plus" className="w-4 h-4" />
                          </button>
                        </div>
                        <button type="button" onClick={() => onRemove(item.product.sourceId)} className="w-9 h-9 rounded-full grid place-items-center text-red-600 hover:bg-red-50" aria-label="Quitar producto">
                          <Icon name="Trash" className="w-4 h-4" />
                        </button>
                      </div>
                    </div>
                  </div>
                )) : (
                  <div className="rounded-3xl border border-dashed border-[color:var(--border)] bg-white/70 p-8 text-center">
                    <Icon name="ShoppingBag" className="w-10 h-10 mx-auto text-[color:var(--bronze)]" />
                    <p className="mt-4 font-serif text-2xl text-[color:var(--coffee)]">Aún no agregaste productos</p>
                    <p className="mt-2 text-sm text-[color:var(--muted-foreground)]">Agrega tus favoritos desde el catálogo para registrar tu pedido.</p>
                  </div>
                )}
              </div>

              {cart.length ? (
                <form onSubmit={submitOrder} className="mt-6 rounded-3xl border border-[color:var(--border)] bg-white p-5 md:p-6">
                  <div className="flex items-center justify-between gap-4 pb-5 border-b border-[color:var(--border)]">
                    <span className="text-sm text-[color:var(--muted-foreground)]">Total estimado</span>
                    <strong className="font-serif text-3xl text-[color:var(--olive)]">{money.format(total)}</strong>
                  </div>
                  <div className="mt-5 grid sm:grid-cols-2 gap-4">
                    <CartField label="Nombres">
                      <input required value={form.nombres} onChange={onChange("nombres")} className={cartInputCls} />
                    </CartField>
                    <CartField label="Apellidos">
                      <input value={form.apellidos} onChange={onChange("apellidos")} className={cartInputCls} />
                    </CartField>
                    <CartField label="Teléfono">
                      <input required value={form.telefono} onChange={onChange("telefono")} className={cartInputCls} />
                    </CartField>
                    <CartField label="Correo">
                      <input type="email" value={form.email} onChange={onChange("email")} className={cartInputCls} />
                    </CartField>
                    <CartField label="Ciudad">
                      <input value={form.ciudad} onChange={onChange("ciudad")} className={cartInputCls} />
                    </CartField>
                    <CartField label="Método de pago">
                      <select required value={form.metodo_pago} onChange={onChange("metodo_pago")} className={cartInputCls}>
                        <option value="por_coordinar">Por coordinar</option>
                        <option value="yape">Yape</option>
                        <option value="plin">Plin</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="efectivo">Efectivo</option>
                      </select>
                    </CartField>
                    <div className="sm:col-span-2">
                      <CartField label="Dirección de entrega">
                        <input value={form.direccion} onChange={onChange("direccion")} className={cartInputCls} />
                      </CartField>
                    </div>
                    <div className="sm:col-span-2">
                      <CartField label="Referencia o notas">
                        <textarea rows={3} value={form.referencia || form.notas} onChange={(event) => {
                          setForm((current) => ({ ...current, referencia: event.target.value, notas: event.target.value }));
                        }} className={`${cartInputCls} resize-none`} />
                      </CartField>
                    </div>
                  </div>

                  {status.state === "error" ? (
                    <p className="mt-4 text-sm text-red-600">{status.message}</p>
                  ) : null}

                  <button
                    type="submit"
                    disabled={status.state === "loading"}
                    className="mt-6 w-full inline-flex items-center justify-center gap-2 rounded-full px-5 py-3.5 text-white font-medium disabled:opacity-60"
                    style={{ background: "linear-gradient(135deg, var(--olive), var(--bronze))" }}
                  >
                    <Icon name="ShoppingBag" className="w-4 h-4" />
                    {status.state === "loading" ? "Registrando..." : "Registrar pedido"}
                  </button>
                </form>
              ) : null}
            </>
          )}
        </div>
      </aside>
    </div>
  );
}

const cartInputCls =
  "w-full rounded-2xl bg-[color:var(--soft-cream)] border border-[color:var(--border)] px-4 py-3 text-[14px] text-[color:var(--coffee)] outline-none focus:border-[color:var(--olive)] focus:bg-white transition";

function CartField({ label, children }) {
  return (
    <label className="block">
      <span className="text-[10px] uppercase tracking-[0.2em] text-[color:var(--muted-foreground)] mb-1.5 block">{label}</span>
      {children}
    </label>
  );
}

function Reveal({ children, delay = 0, as = "div", className = "", priority = false }) {
  const ref = useRef(null);
  const [visible, setVisible] = useState(false);
  useEffect(() => {
    const reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (reduced) {
      setVisible(true);
      return;
    }
    if (priority) {
      const frame = requestAnimationFrame(() => setVisible(true));
      return () => cancelAnimationFrame(frame);
    }
    const obs = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => {
          if (e.isIntersecting) {
            setVisible(true);
            obs.disconnect();
          }
        });
      },
      { threshold: 0.12, rootMargin: "0px 0px -40px 0px" }
    );
    if (ref.current) obs.observe(ref.current);
    return () => obs.disconnect();
  }, [priority]);
  const Tag = as;
  return (
    <Tag
      ref={ref}
      className={`${className}`}
      style={{
        transition: "opacity .45s ease, transform .45s cubic-bezier(.2,.7,.2,1)",
        transitionDelay: `${delay}ms`,
        opacity: visible ? 1 : 0,
        transform: visible ? "translateY(0)" : "translateY(18px)",
      }}
    >
      {children}
    </Tag>
  );
}

Object.assign(window, {
  Icon,
  LeafSprig,
  GradientButton,
  WhatsAppButton,
  SectionHeader,
  Pill,
  FAQAccordion,
  ProductImage,
  ProductCard,
  CategoryCard,
  ProductModal,
  CartDrawer,
  Reveal,
});
