<p>決済ページへリダイレクトします。</p>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ $publicKey }}");
    stripe.redirectToCheckout({
        sessionId: "{{ $session->id }}"
    }).then(function (result) {
        if (result.error) {
            alert(result.error.message || "リダイレクトに失敗しました。");
            window.location.href = "{{ route('items.index') }}";
        }
    });
</script>
