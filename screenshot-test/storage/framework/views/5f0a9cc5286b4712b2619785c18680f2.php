<style>
        :root {
            --bg: #f8f9fa;
            --bg-card: #ffffff;
            --text: #212529;
            --muted: #6c757d;
            --border: #dee2e6;
            --accent-judol: #dc3545;
            --accent-bersih: #28a745;
            --accent-kosong: #6c757d;
            --accent-blur: #6f42c1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
        }

        .navbar {
            background: var(--bg-card);
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar .logo {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text);
            text-decoration: none;
        }

        .navbar .nav-links a {
            margin-left: 1.5rem;
            color: var(--muted);
            text-decoration: none;
            font-weight: 500;
            transition: color .2s;
        }
        .navbar .nav-links a:hover {
            color: var(--text);
        }

        .container {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card h2 {
            margin-top: 0;
            font-size: 1.1rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }

        .summary-card {
            padding: 1rem;
            border-radius: 6px;
            text-align: center;
        }
        .summary-card.judol { background: #f8d7da; color: var(--accent-judol); }
        .summary-card.bersih { background: #d1e7dd; color: var(--accent-bersih); }
        .summary-card.kosong { background: #e2e3e5; color: var(--accent-kosong); }
        .summary-card.blur { background: #e4d4f7; color: var(--accent-blur); }
        .summary-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: .2rem;
        }
        .summary-label {
            font-size: .85rem;
            color: var(--muted);
        }

        .nav-pill {
            display: inline-block;
            padding: .4rem .8rem;
            background: #e9ecef;
            border-radius: 20px;
            font-size: .8rem;
            color: var(--muted);
            margin-right: .3rem;
            margin-bottom: .3rem;
        }
        .nav-pill.active {
            background: var(--bg);
            color: var(--text);
        }

        .table thead th {
            background: #f1f3f5;
            border-bottom: 2px solid var(--border);
        }

        .btn-action {
            display: inline-block;
            padding: .5rem 1rem;
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 4px;
            font-size: .85rem;
            cursor: pointer;
            transition: background .2s;
            text-decoration: none;
        }
        .btn-action:hover {
            background: #dde5e8;
        }

        .flash {
            background: #e7f3e7;
            border: 1px solid #cbd6c8;
            padding: .7rem 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            color: #0f5132;
        }

        .no-data {
            color: var(--muted);
            padding: 2rem;
            text-align: center;
        }
</style><?php /**PATH C:\File Rubenz TA\Projek Kecil2an\test\screenshot-test\resources\views/sections/styles.blade.php ENDPATH**/ ?>