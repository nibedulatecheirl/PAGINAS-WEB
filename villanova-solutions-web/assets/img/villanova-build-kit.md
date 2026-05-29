# 🚀 Villanova Solutions Peru — Kit de Construcción Completo

Este documento contiene **todo lo que necesitas** para generar la landing page funcional al 100%:

1. **Prompts para generar imágenes** (DALL·E 3 / ChatGPT y Gemini Imagen)
2. **Prompts para generar videos** (Sora / Veo / Runway / Kling) — opcional
3. **Prompt maestro para Claude** que arma TODAS las secciones

> Identidad clave: **azul cobalto + navy + blanco**, **minimalista**, **mucho movimiento**, y **sound waves / ondas de sonido** como sello distintivo de la marca.

---

## 📐 Mapa de secciones que tendrá la landing

| # | Sección | Propósito |
|---|---------|-----------|
| 1 | Navbar | Navegación + CTA principal |
| 2 | Hero | Headline, dashboard preview, sound waves de fondo |
| 3 | Logo cloud | Marcas tecnológicas con las que trabajan |
| 4 | Servicios | 3 pilares (Productos / Software / Automatización) |
| 5 | Agentes de IA | Showcase con waveform animado |
| 6 | Cómo trabajamos | Proceso de 4 pasos |
| 7 | Stats | Números de confianza |
| 8 | Productos destacados | Mini catálogo de tecnología |
| 9 | Testimonios | 3 clientes peruanos |
| 10 | FAQ | 6 preguntas frecuentes |
| 11 | CTA final | Conversión |
| 12 | Footer | Contacto + redes + mapa de sitio |

---

# 🎨 PARTE 1 — PROMPTS PARA IMÁGENES

> **Importante**: el diseño está pensado para usar **muy pocas imágenes** (la mayoría son SVG/CSS para mantener minimalismo y rapidez). Solo las que listo abajo son útiles. Las marcadas como *opcional* puedes saltarlas y usar iniciales/placeholders.

## 🖼️ Imagen 1 — Open Graph (para compartir en redes)
**Uso:** meta tag `og:image`. Aparece cuando comparten el link en WhatsApp, Facebook, LinkedIn.
**Dimensiones:** 1200 × 630 px
**Plataforma sugerida:** ChatGPT (DALL·E 3) o Gemini Imagen

```
A minimalist, elegant Open Graph banner for a Peruvian tech company called "Villanova Solutions Peru". White background with subtle horizontal sound wave lines flowing across in cobalt blue (#1E5FED). Bold dark navy serif text reading "Tecnología que Impulsa tu Negocio" on the left side, with "Villanova Solutions Peru" as a smaller wordmark above it. On the right side, a clean stylized abstract dashboard preview floating with soft shadow. Modern minimalist editorial design, lots of negative space, premium SaaS aesthetic. No people, no clutter. Aspect ratio 1200x630.
```

---

## 🖼️ Imagen 2 — Favicon
**Uso:** ícono de pestaña del navegador.
**Dimensiones:** 512 × 512 px (luego se exporta a 32x32, 16x16)
**Plataforma sugerida:** ChatGPT o Gemini

```
A minimalist square app icon for "Villanova Solutions Peru". A stylized geometric letter "V" in solid white inside a rounded square gradient background going from deep navy (#0A1628) at top-left to cobalt blue (#1E5FED) at bottom-right. The V is bold, sans-serif, geometric, slightly modern. Clean, no extra elements, no text, no shadows. Flat design. 512x512 pixels, centered.
```

---

## 🖼️ Imágenes 3, 4, 5 — Avatares de testimonios (*opcional*)
**Uso:** fotos circulares de los 3 clientes en la sección de testimonios.
**Dimensiones:** 400 × 400 px cada una

> **Alternativa sin generar imágenes:** usar iniciales en círculos con gradiente. Es más rápido y consistente.

### Avatar 1 — Carlos M. (CEO de retail)
```
Professional headshot photo of a Peruvian businessman in his late 30s, short dark hair, light beard, wearing a navy blazer over a white shirt, friendly confident smile, neutral light gray studio background, soft natural lighting, sharp focus, photorealistic, corporate portrait style, looking slightly off-camera. Square 1:1 aspect ratio.
```

### Avatar 2 — María L. (Gerente de operaciones)
```
Professional headshot photo of a Peruvian businesswoman in her early 40s, long straight dark hair, warm smile, wearing a cream blouse, neutral light gray studio background, soft natural lighting, sharp focus, photorealistic, corporate portrait style. Square 1:1 aspect ratio.
```

### Avatar 3 — Jorge S. (Fundador startup)
```
Professional headshot photo of a young Peruvian entrepreneur in his late 20s, modern haircut, clean shaven, wearing a black t-shirt, neutral light gray studio background, soft natural lighting, relaxed confident expression, photorealistic, modern startup founder portrait. Square 1:1 aspect ratio.
```

---

## 🖼️ Imágenes 6, 7, 8 — Productos destacados (*opcional*)
**Uso:** sección de productos tecnológicos.
**Dimensiones:** 800 × 800 px (cuadradas, fondo blanco/limpio)

> **Alternativa**: usa las fotos reales de tus productos importados. Es más auténtico que renders generados.

### Producto 1 — Laptop gaming
```
Product photography of a sleek modern gaming laptop, open at 110 degrees, screen showing an abstract blue wave visualization. Pure white seamless background, soft shadow underneath, slight three-quarter angle view, professional e-commerce product shot, high detail on keyboard backlight, clean minimalist composition. 1:1 square aspect ratio.
```

### Producto 2 — Setup de oficina (monitor + accesorios)
```
Product photography arrangement: a thin modern 4K monitor, a wireless keyboard, mouse, and a pair of black over-ear headphones, all arranged on a white seamless background with soft shadows. Top-down slightly angled view, clean minimalist composition, professional e-commerce style, no people, no logos. 1:1 square aspect ratio.
```

### Producto 3 — Componente PC (tarjeta gráfica o procesador)
```
Product photography of a high-end gaming graphics card with dual fans and RGB accents in cobalt blue, floating against a pure white background with soft shadow underneath. Three-quarter angle, professional studio lighting, sharp detail on the metallic fins and connectors, premium e-commerce product shot. 1:1 square aspect ratio.
```

---

## 🖼️ Imagen 9 — Foto del equipo o caso de uso (*opcional*)
**Uso:** sección "Cómo trabajamos" o "Sobre nosotros".

```
Photography of a modern minimalist tech office in Lima Peru, two people working together at a clean desk with dual monitors showing code and data dashboards, lots of natural light through large windows, white walls, plants, candid working moment, photorealistic, warm professional atmosphere, shallow depth of field. 16:9 aspect ratio.
```

---

# 🎬 PARTE 2 — PROMPTS PARA VIDEOS (opcionales)

> **Nota importante**: el diseño base **no requiere video** — las sound waves están hechas en SVG/CSS y se ven más limpias y cargan más rápido. Pero si quieres un video alternativo o de fondo para una sección, aquí van:

## 🎥 Video 1 — Hero alternativo (loop de sound waves)
**Uso:** fondo del hero si quieres reemplazar las sound waves CSS.
**Duración:** 8–12 segundos loop
**Plataforma:** Sora, Google Veo, Runway Gen-3, Kling AI

```
Abstract minimalist background video: thin horizontal sound wave lines flowing slowly from right to left across a pure white background. Multiple layered waves at different speeds and opacities, in cobalt blue (#1E5FED) and very light blue tones. Gentle rhythmic motion, like an audio waveform breathing. Soft radial vignette mask so edges fade to white. Premium SaaS aesthetic. No text, no objects, no people. Seamless loop. 16:9 aspect ratio, 1920x1080.
```

## 🎥 Video 2 — Demo del dashboard (*opcional*)
**Uso:** botón "Ver video" en CTA, modal de demo.
**Duración:** 30–60 segundos
**Plataforma:** mejor grabarlo manualmente del producto real con Loom o ScreenFlow. Si lo quieres generar:

```
Screen recording style video: a smooth animated demonstration of a SaaS dashboard interface for an AI agent platform. Camera slowly pans and zooms across a clean white dashboard showing a sidebar menu, conversation analytics, an animated audio waveform card pulsing in cobalt blue, and a table of recent automations. UI elements appear with subtle fade-up animations. Cursor moves naturally clicking buttons. Modern, minimalist, premium feel, no people. 16:9 aspect ratio.
```

---

# 🤖 PARTE 3 — PROMPT MAESTRO PARA CLAUDE

> Copia **todo el bloque siguiente** y pásalo a Claude (claude.ai). Te generará la página completa con todas las secciones en un solo archivo HTML autónomo, o como proyecto React si lo prefieres.

---

## 📋 PROMPT — pégalo entero a Claude

````
Construye una landing page completa, funcional y de una sola página para "Villanova Solutions Peru", una empresa peruana que ofrece tres servicios: desarrollo de software a medida, automatización de procesos con agentes de inteligencia artificial, e importación/venta de productos tecnológicos al por mayor y menor.

ENTREGA: un único archivo HTML autocontenido (HTML + CSS + JS inline) que abra y funcione en cualquier navegador moderno sin dependencias de build. Usa Google Fonts directamente. No uses frameworks JS, solo JS vanilla cuando sea necesario.

═══════════════════════════════════════════
IDENTIDAD VISUAL (no negociable)
═══════════════════════════════════════════

Concepto: minimalismo refinado + mucho movimiento + sound waves (ondas de sonido) como sello distintivo de la marca. Premium SaaS, estilo editorial.

PALETA (CSS variables en :root):
  --bg: #FFFFFF
  --bg-soft: #F7F9FC
  --fg: #0A1628 (navy profundo)
  --fg-muted: #6B7785
  --fg-faint: #98A2B3
  --accent: #1E5FED (azul cobalto — color de la marca)
  --accent-deep: #1547C2
  --accent-soft: #EAF0FF
  --border: #E6EAF0
  --border-soft: #EEF1F5
  --success: #16A34A
  --warning: #D97706
  --danger: #DC2626
  --radius: 12px / --radius-lg: 20px
  --shadow-card: 0 1px 2px rgba(10,22,40,0.04), 0 0 0 1px rgba(10,22,40,0.04)
  --shadow-elevated: 0 25px 60px -20px rgba(10,22,40,0.18), 0 0 0 1px rgba(10,22,40,0.05)

TIPOGRAFÍA (Google Fonts):
  Display: 'Fraunces' (variable, con itálica) — para titulares, palabras destacadas en cursiva
  Body: 'Plus Jakarta Sans' (400/500/600/700) — para todo el cuerpo y UI

  Toda palabra destacada dentro de un titular va envuelta en <em> y se renderiza en Fraunces itálico, color --accent.

ANIMACIONES (obligatorias):
  - Entrada del hero con fade-up escalonado (delays 0.05s → 0.55s)
  - Sound waves SVG fluyendo horizontalmente en el fondo del hero (7 paths con keyframes wave-flow + wave-fade desfasados)
  - Sonar: 3 anillos concéntricos pulsando detrás del titular cada 6s
  - Waveform de barras animadas (56 barras, alturas con envolvente sinusoidal, delays escalonados) dentro de la card del agente IA
  - Hover en links con subrayado animado de izquierda a derecha
  - Scroll reveal: cada sección hace fade-up al entrar al viewport (IntersectionObserver)
  - Respetar @media (prefers-reduced-motion: reduce)

═══════════════════════════════════════════
SECCIÓN 1 — NAVBAR
═══════════════════════════════════════════
- Sticky en top con backdrop-filter blur cuando se scrollea
- Izquierda: logo "Villanova / Perú" con cuadrito gradient navy→cobalto que contiene una V blanca
- Centro (oculto en móvil): links → Inicio · Servicios · Productos · Nosotros · Contacto
- Derecha: botón "Contáctanos" pill negro
- Hamburguesa funcional en móvil que despliega menú overlay

═══════════════════════════════════════════
SECCIÓN 2 — HERO
═══════════════════════════════════════════
Altura: min-h-screen, contenido centrado.

Fondo (z-0):
- 7 paths SVG con curvas Q...T (ondas de sonido) que se desplazan con keyframe wave-flow (translateX -25%) en 14s loop, y wave-fade (opacity 0→0.18→0) escalonado.
- Mask radial para fade en bordes.
- 3 sonar-rings absolutos detrás del titular, animación sonar 6s expandiendo de scale(0.4) a scale(1.2).

Contenido (z-10, fade-up escalonado):
1. Badge: pill blanco con borde, dot cobalto pulsante, texto "Agentes de IA disponibles ahora ✦"
2. Headline display, ~5rem, line-height 0.98: "Tecnología que <em>Impulsa</em> tu negocio"
3. Subhead: "Desarrollamos software a medida, automatizamos procesos con agentes de inteligencia artificial y proveemos los mejores productos tecnológicos para empresas en todo el Perú."
4. CTAs: botón pill negro "Agenda una demo" + botón circular blanco con ícono play
5. Dashboard preview: contenedor frosted glass con sidebar (Inicio · Agentes IA badge 12 · Automatizaciones · Productos · Pagos · Clientes · sección Flujos: Integraciones · Notificaciones · Ajustes), main con greeting "Bienvenido, Carlos", botones de acción pill (Nuevo agente primario cobalto, + Automatización, ⇄ Integrar, + Cliente, + Producto, ⤓ Reporte), dos cards:
   - Card 1 "Agente Soporte Ventas": 1,284 conversaciones, stats ▲+312 / ▼−18, y waveform de 56 barras animadas (este es el momento donde el sound wave se hace literal — barras con gradiente cobalto→azul profundo, animación barPulse 1.8s scaleY 0.3↔1, delays escalonados generados con JS)
   - Card 2 "Servicios activos": 3 filas con dot de estado, label, meta texto, valor en soles (Desarrollo Web S/ 48,500 · Automatización S/ 22,150 · Importación HW S/ 73,820)
   Tabla "Actividad reciente" con 4 filas en soles (fechas 2026, descripciones contextuales peruanas: Importación · Laptops ASUS TUF, Pago cliente · Sistema POS, Agente IA · Implementación call center, Suscripción · Automatización mensual)

═══════════════════════════════════════════
SECCIÓN 3 — LOGO CLOUD (marcas con las que trabajan)
═══════════════════════════════════════════
- Eyebrow: "Distribuimos productos de las marcas líderes mundiales"
- Fila horizontal con 7 logos en escala de grises (ASUS, AMD, NVIDIA, Lenovo, HP, Logitech, Razer)
- Si no tienes SVGs, usa text-logos en font display con su tipografía aproximada
- Animación: scroll horizontal infinito sutil (marquee CSS) o estático con hover que satura el color

═══════════════════════════════════════════
SECCIÓN 4 — SERVICIOS (3 pilares)
═══════════════════════════════════════════
Eyebrow: "Lo que hacemos"
Título: "Tres formas de <em>potenciar</em> tu negocio"

Grid de 3 cards (responsive: 1 col móvil → 3 cols desktop), cada una:
- Ícono SVG grande arriba (24×24 en círculo cobalto suave)
- Título grande
- Descripción
- Lista de 3-4 bullets con check
- Link "Conocer más →" al final

Card 1 — Productos Tecnológicos
  Ícono: carrito de compras
  Título: "Productos Tecnológicos"
  Desc: "Equipos y accesorios originales de las mejores marcas, con garantía y envío a todo el Perú."
  Bullets: Laptops y PCs · Componentes y periféricos · Accesorios gaming y oficina · Garantía oficial

Card 2 — Desarrollo de Software
  Ícono: corchetes </>
  Título: "Desarrollo de Software"
  Desc: "Sistemas web, aplicaciones móviles y soluciones a medida diseñadas para tu sector."
  Bullets: Webs y e-commerce · Apps iOS/Android · Sistemas ERP y POS · Integraciones API

Card 3 — Automatización de Procesos
  Ícono: engranaje con destello
  Título: "Automatización de Procesos"
  Desc: "Agentes de inteligencia artificial que aprenden, ejecutan y liberan a tu equipo de tareas repetitivas."
  Bullets: Agentes de IA personalizados · Automatización de ventas · Chatbots y voicebots · Flujos sin código

═══════════════════════════════════════════
SECCIÓN 5 — AGENTES DE IA (showcase destacado)
═══════════════════════════════════════════
Fondo: bg navy --fg con texto blanco (inversión de tema para llamar atención)

Layout split: 50/50 desktop, stack móvil.
Izquierda:
  Eyebrow cobalto: "Nuevo · Agentes de IA"
  Título: "Tu equipo, ahora con <em>voz</em> propia."
  Descripción: "Diseñamos agentes que conversan con tus clientes, califican leads, atienden soporte y cierran ventas — 24/7, en español natural, integrados a tu CRM."
  Bullets check (4): Atención multicanal · Voz humanizada · Integraciones nativas · Aprende de tu negocio
  CTA: pill cobalto "Solicitar demo"

Derecha:
  Visualización dramática del agente en acción: card flotante con avatar circular pulsando, transcripción de conversación en vivo (3-4 mensajes alternados usuario/agente), debajo un waveform horizontal animado más grande y prominente que el del hero. Las barras se mueven con la "voz" del agente.

═══════════════════════════════════════════
SECCIÓN 6 — CÓMO TRABAJAMOS (proceso 4 pasos)
═══════════════════════════════════════════
Título: "Un proceso <em>simple</em>, resultados reales."

4 columnas en desktop, vertical en móvil. Cada paso:
- Número grande en Fraunces itálico (01, 02, 03, 04) en cobalto muy claro detrás
- Título
- Descripción corta (2 líneas)

01. Diagnóstico — "Conversamos contigo para entender tu negocio, objetivos y oportunidades de mejora."
02. Propuesta — "Diseñamos una solución a medida con tiempos, costos y entregables claros."
03. Implementación — "Construimos, integramos y desplegamos sin interrumpir tu operación actual."
04. Acompañamiento — "Te capacitamos y damos soporte continuo para que la inversión rinda al máximo."

Visual: línea conectora horizontal punteada cobalto entre los pasos en desktop.

═══════════════════════════════════════════
SECCIÓN 7 — STATS (números de confianza)
═══════════════════════════════════════════
Fondo blanco, una fila de 4 stats grandes:
- "+50" Empresas peruanas confían en nosotros
- "+120" Proyectos completados
- "24/7" Soporte técnico especializado
- "100%" Productos originales con garantía

Cada número en Fraunces itálico tamaño 4rem, cobalto. Label debajo en cuerpo, muted.
Animación: counter-up al hacer scroll dentro del viewport.

═══════════════════════════════════════════
SECCIÓN 8 — PRODUCTOS DESTACADOS
═══════════════════════════════════════════
Eyebrow: "Villanova Store"
Título: "Tecnología <em>premium</em>, al mejor precio."

Grid 3 cols (1 col móvil, 2 tablet) con 6 productos. Cada card:
- Imagen del producto en bg gris claro (o placeholder con ícono)
- Badge categoría arriba izquierda
- Nombre del producto
- Precio en soles "S/ 4,250"
- Pequeño botón "Ver →"

Productos sugeridos:
1. Laptop ASUS TUF Gaming F15 — S/ 4,850 — Gaming
2. Monitor LG UltraGear 27" 144Hz — S/ 1,790 — Monitores
3. Audífonos Sony WH-1000XM5 — S/ 1,290 — Audio
4. Procesador AMD Ryzen 7 7700X — S/ 1,150 — Componentes
5. Teclado mecánico Logitech G Pro X — S/ 690 — Periféricos
6. SSD Samsung 990 Pro 2TB — S/ 980 — Almacenamiento

Footer de sección: link "Ver catálogo completo →"

═══════════════════════════════════════════
SECCIÓN 9 — TESTIMONIOS
═══════════════════════════════════════════
Título: "Lo que dicen <em>nuestros</em> clientes."

Carrusel/grid de 3 testimonios. Cada card:
- Comilla grande en Fraunces itálico arriba
- Quote
- Avatar circular (placeholder con iniciales en gradient cobalto si no hay foto)
- Nombre + cargo + empresa

Testimonios:
1. "Implementamos un agente de IA para atender consultas en nuestra tienda y las conversiones subieron 38% en dos meses. El equipo de Villanova entendió nuestro negocio mejor que muchos consultores." — Carlos M., CEO, Retail Andino

2. "Pasamos de Excel a un sistema POS hecho a medida en seis semanas. Hoy controlamos inventario en tiempo real desde tres locales." — María L., Gerente de Operaciones, Distribuidora Lima Norte

3. "Necesitábamos automatizar la cotización de proyectos. Lo que antes tomaba dos horas, ahora un agente lo hace en treinta segundos." — Jorge S., Fundador, Estudio Creativo Pacífico

═══════════════════════════════════════════
SECCIÓN 10 — FAQ
═══════════════════════════════════════════
Título: "Preguntas <em>frecuentes</em>."

6 acordeones con animación de despliegue suave:

Q: ¿Atienden a clientes fuera de Lima?
A: Sí. Trabajamos con empresas de todo el Perú. Las reuniones iniciales son virtuales y los envíos de productos tecnológicos llegan a las 24 regiones.

Q: ¿En cuánto tiempo entregan un proyecto de software?
A: Depende del alcance. Un sistema simple puede estar listo en 4 a 6 semanas. Plataformas complejas pueden tomar de 3 a 6 meses. Te damos un cronograma claro antes de empezar.

Q: ¿Qué garantía tienen los productos que importan?
A: Todos los productos son 100% originales con garantía oficial del fabricante (entre 12 y 36 meses según el equipo) más nuestra garantía adicional de servicio técnico local.

Q: ¿Los agentes de IA hablan español peruano?
A: Sí. Entrenamos cada agente con el vocabulario, modismos y tono de tu negocio. Hablan español natural y pueden manejar consultas técnicas, comerciales y de soporte.

Q: ¿Puedo integrar el agente a mi WhatsApp Business y CRM?
A: Sí. Soportamos integraciones con WhatsApp Business API, HubSpot, Salesforce, Zoho, Pipedrive y la mayoría de CRMs populares. También hacemos integraciones a medida.

Q: ¿Cuánto cuesta? ¿Tienen planes?
A: Cada solución es a medida porque cada negocio es distinto. Agenda una llamada de 30 minutos sin costo y te damos una propuesta con precio fijo en menos de 48 horas.

═══════════════════════════════════════════
SECCIÓN 11 — CTA FINAL
═══════════════════════════════════════════
Fondo: gradient sutil de --bg-soft a --accent-soft.
Centrado, una sola columna:
- Sound wave decorativa pequeña arriba
- Título grande: "¿Listo para <em>impulsar</em> tu negocio?"
- Subtítulo: "Conversemos 30 minutos. Sin costo, sin compromiso."
- Botones: pill cobalto "Agenda tu demo" + outline "Escríbenos por WhatsApp" (link wa.me/51984340361)

═══════════════════════════════════════════
SECCIÓN 12 — FOOTER
═══════════════════════════════════════════
Fondo: --fg (navy profundo), texto blanco/muted.

Layout 4 columnas en desktop:

Columna 1 (más ancha): Logo Villanova + tagline + dirección/zona Perú + redes sociales (Facebook, Instagram, WhatsApp, LinkedIn) con íconos circulares.

Columna 2 — Empresa: Inicio · Servicios · Productos · Nosotros · Casos de éxito · Blog

Columna 3 — Servicios: Desarrollo de Software · Agentes de IA · Automatización · Importación tech · Soporte · Consultoría

Columna 4 — Contacto: WhatsApp +51 984 340 361 · Email villanovasolutionsperu@gmail.com · @villanovasolutionsperu

Línea inferior separada con border-top sutil:
- Izquierda: © 2026 Villanova Solutions Peru. Todos los derechos reservados.
- Derecha: Términos · Privacidad · Cookies

═══════════════════════════════════════════
DETALLES TÉCNICOS
═══════════════════════════════════════════
- Mobile-first y completamente responsive (breakpoints 640/768/1024/1280)
- Semantic HTML5 (header, nav, main, section, article, footer)
- ARIA labels en botones de íconos
- Lazy loading en imágenes
- Smooth scroll para anchor links
- Meta tags completos: title, description, og:image, og:title, og:description, twitter:card
- favicon link al SVG inline
- Open Graph: "Villanova Solutions Peru — Tecnología que impulsa tu negocio"
- lang="es"
- Scroll reveal con IntersectionObserver para cada sección
- Performance: nada de imágenes pesadas innecesarias, todo SVG/CSS donde se pueda

═══════════════════════════════════════════
COPY: Spanish from Peru — natural, profesional, sin anglicismos forzados.
TODO embebido en un solo archivo HTML autónomo.
LISTO PARA PRODUCCIÓN.
````

---

# 🛠️ Cómo usar este kit

## Opción A — Rápida (sin imágenes propias)
1. Copia el **prompt maestro** de la Parte 3
2. Pégalo en Claude (claude.ai)
3. Claude genera todo el HTML
4. Lo abres en el navegador → listo

## Opción B — Completa (con tus assets)
1. **Genera el favicon y el OG image** con los prompts de la Parte 1 (Imagen 1 y 2). Suben los archivos a Imgur o tu CDN.
2. **Toma fotos reales** de 6 productos que vendas — mejor que renders generados.
3. **Pide al cliente** sus 3 testimonios reales con foto si pueden.
4. **Pega el prompt maestro a Claude** y dile además: "Aquí están las URLs reales de imágenes: [pega las URLs]"
5. Claude integra todo y entrega la versión final.

## Opción C — Si quieres versión React/Next.js
Cambia la primera línea del prompt maestro de:
> "ENTREGA: un único archivo HTML autocontenido..."

a:
> "ENTREGA: un proyecto React/Next.js con Tailwind CSS, framer-motion para animaciones, lucide-react para íconos, shadcn/ui para componentes base. Una página principal en `app/page.tsx` con componentes separados por sección en `components/sections/`."

---

# 📎 Datos reales de Villanova para incluir

- **WhatsApp:** +51 984 340 361 → link: `https://wa.me/51984340361`
- **Email:** villanovasolutionsperu@gmail.com
- **Instagram:** @villanovasolutionsperu
- **Eslogan oficial:** "Tecnología que impulsa tu negocio"
- **Tres pilares:** Productos Tecnológicos / Desarrollo de Software / Automatización de Procesos
- **Tagline secundario:** "Innovación, calidad y confianza para llevar tu negocio al siguiente nivel"

---

¿Necesitas que te genere prompts adicionales (para más secciones, otros idiomas, variantes A/B del headline) o que adapte esto para un constructor específico (Webflow, Framer, Wix Studio)? Me dices.
