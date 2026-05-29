function App() {
  const [detail, setDetail] = React.useState(null);
  const [cartOpen, setCartOpen] = React.useState(false);
  const [cart, setCart] = React.useState([]);
  const [catalog, setCatalog] = React.useState({
    products: [],
    categories: [],
    promos: [],
    loading: true,
    error: null,
  });

  React.useEffect(() => {
    let alive = true;

    BENI.api.loadCatalog()
      .then((data) => {
        if (!alive) return;
        BENI.products = data.products;
        BENI.categories = data.categories;
        BENI.promos = data.promos;
        setCatalog({ ...data, loading: false, error: null });
      })
      .catch((error) => {
        if (!alive) return;
        setCatalog((current) => ({ ...current, loading: false, error: error.message || "No se pudo cargar el catálogo." }));
      });

    return () => {
      alive = false;
    };
  }, []);

  const addToCart = React.useCallback((product) => {
    if (!product?.available) return;

    setCart((items) => {
      const maxQuantity = product.controlsStock ? Math.max(1, Math.floor(Number(product.stock || 0))) : 999;
      const existing = items.find((item) => item.product.sourceId === product.sourceId);

      if (existing) {
        return items.map((item) =>
          item.product.sourceId === product.sourceId
            ? { ...item, quantity: Math.min(maxQuantity, item.quantity + 1) }
            : item
        );
      }

      return [...items, { product, quantity: 1 }];
    });
    setCartOpen(true);
  }, []);

  const updateCartQuantity = React.useCallback((sourceId, quantity) => {
    setCart((items) =>
      items
        .map((item) => {
          if (item.product.sourceId !== sourceId) return item;

          const maxQuantity = item.product.controlsStock
            ? Math.max(1, Math.floor(Number(item.product.stock || 0)))
            : 999;

          return { ...item, quantity: Math.min(maxQuantity, Math.max(0, quantity)) };
        })
        .filter((item) => item.quantity > 0)
    );
  }, []);

  const removeFromCart = React.useCallback((sourceId) => {
    setCart((items) => items.filter((item) => item.product.sourceId !== sourceId));
  }, []);

  const clearCart = React.useCallback(() => setCart([]), []);
  const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
  const brandNames = React.useMemo(() => {
    return [...new Set(catalog.products.map((product) => product.brand).filter(Boolean))];
  }, [catalog.products]);

  return (
    <div className="min-h-screen overflow-x-clip" style={{ background: "var(--background)" }}>
      <Navbar cartCount={cartCount} onOpenCart={() => setCartOpen(true)} />
      <main>
        <Hero brands={brandNames} />
        <BrandStrip brands={brandNames} />
        <ProductMarquee products={catalog.products} loading={catalog.loading} />
        <Categories categories={catalog.categories} loading={catalog.loading} />
        <FeaturedProducts
          products={catalog.products}
          loading={catalog.loading}
          error={catalog.error}
          onDetail={setDetail}
          onAddToCart={addToCart}
        />
        <SunProtection />
        <Routines />
        <Promos promos={catalog.promos} loading={catalog.loading} />
        <Benefits />
        <HowToBuy />
        <CtaFinal />
        <FaqSection />
        <ContactSection />
      </main>
      <Footer categories={catalog.categories} />
      <FloatingWhatsApp />
      <CartDrawer
        open={cartOpen}
        cart={cart}
        onClose={() => setCartOpen(false)}
        onQuantity={updateCartQuantity}
        onRemove={removeFromCart}
        onClear={clearCart}
      />
      <ProductModal product={detail} onClose={() => setDetail(null)} onAddToCart={addToCart} />
    </div>
  );
}

const root = ReactDOM.createRoot(document.getElementById("root"));
root.render(<App />);
