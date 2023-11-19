<template>
  <div class="fixed inset-0 h-full w-full bg-neutral-900 bg-opacity-80 z-10">
    <div
      class="flex flex-col relative md:top-20 h-screen md:h-auto md:max-h-[80vh] w-screen md:w-128 py-9 mx-auto md:rounded-md bg-neutral-800 shadow-lg"
    >
      <slot />
    </div>
  </div>
</template>
