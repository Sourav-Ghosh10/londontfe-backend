<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - London TFE Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif; }
    </style>
</head>
<body class="flex items-center justify-center h-screen overflow-hidden bg-[#f6f6f7] dark:bg-gray-900 transition-colors duration-200">

    <div class="w-full max-w-sm">
        <!-- Logo Area -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight transition-colors">London TFE</h1>
        </div>

        <!-- Login Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-300 dark:border-gray-700 p-8 transition-colors duration-200">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Log in</h2>
            
            <form action="/admin" method="GET">
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input type="email" id="email" class="w-full text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors" placeholder="admin@londontfe.com" required>
                </div>

                <div class="mb-6">
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">Forgot password?</a>
                    </div>
                    <input type="password" id="password" class="w-full text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors" placeholder="••••••••" required>
                </div>

                <button type="submit" class="w-full bg-[#008060] hover:bg-[#006e52] text-white font-medium py-2.5 px-4 rounded-md shadow-sm transition-colors">
                    Log in
                </button>
            </form>
        </div>

        <!-- Footer link -->
        <div class="text-center mt-6 text-sm text-gray-500 dark:text-gray-400 border-t border-gray-300 dark:border-gray-700 pt-6 transition-colors">
            <a href="#" class="hover:underline">Help & Support</a>
        </div>
    </div>

</body>
</html>
