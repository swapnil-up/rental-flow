<template>
    <div class="guest-layout">
        <div class="flash" v-if="$page.props.flash.success">
            {{ $page.props.flash.success }}
        </div>
        <div class="flash error" v-if="$page.props.flash.error">
            {{ $page.props.flash.error }}
        </div>
        <main>
            <slot />
        </main>
    </div>
</template>

<style scoped>
.guest-layout {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    font-family: system-ui, -apple-system, sans-serif;
}
.flash {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    padding: 12px 24px;
    background: #d4edda;
    color: #155724;
    border-radius: 4px;
    z-index: 100;
}
.flash.error {
    background: #f8d7da;
    color: #721c24;
}
</style>
