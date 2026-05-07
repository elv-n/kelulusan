<main class="flex-grow flex items-center justify-content-center">
    <section class="w-full py-8 px-4">
        <div class="max-w-md mx-auto">
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="p-8">
                    <div class="mb-8 text-center">
                        <h2 class="text-2xl font-semibold text-gray-900">Login Admin</h2>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" id="formLogin">
                        <div class="space-y-4">
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                                    Username <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="username" id="username" placeholder="Masukkan Username"
                                       class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                       required>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" id="password" placeholder="Masukkan Password"
                                       class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                       required>
                            </div>

                            <div>
                                <button type="submit"
                                        class="w-full px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                    Login
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-6 text-right">
                        <a href="#" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">Lupa Password?</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
