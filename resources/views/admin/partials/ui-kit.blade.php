{{-- Kit de estilos del panel (listados, modales y selectores). Se incluye con @include dentro de @push("css"). --}}
        <style>
            .cv-ui {
                --cv-accent: #0E8A74;
                --cv-accent-soft: rgba(14, 138, 116, .08);
                --cv-accent-ring: rgba(14, 138, 116, .22);
                --cv-text: #1f2937;
                --cv-muted: #6b7280;
                --cv-line: #eceff3;
                --cv-surface: #f7f8fa;
            }

            .cv-ui .cv-modal {
                border: 0;
                border-radius: 18px;
                overflow: hidden;
                box-shadow: 0 24px 60px rgba(15, 23, 42, .18);
            }

            /* Cabecera */
            .cv-ui .cv-head {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 16px;
                padding: 22px 24px 14px;
            }

            .cv-ui .cv-title {
                margin: 0;
                font-size: 1.15rem;
                font-weight: 600;
                color: var(--cv-text);
            }

            .cv-ui .cv-subtitle {
                margin: 6px 0 0;
                font-size: .82rem;
                color: var(--cv-muted);
            }

            .cv-ui .cv-chip {
                display: inline-block;
                margin-left: 4px;
                padding: 2px 10px;
                border-radius: 999px;
                background: var(--cv-accent-soft);
                color: var(--cv-accent);
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .02em;
                font-size: .72rem;
            }

            /* Cuerpo */
            .cv-ui .cv-body {
                padding: 0 24px;
            }

            .cv-ui .cv-toolbar {
                display: flex;
                gap: 12px;
                align-items: center;
                flex-wrap: wrap;
            }

            .cv-ui .cv-search {
                position: relative;
                flex: 1 1 260px;
            }

            .cv-ui .cv-search svg {
                position: absolute;
                left: 12px;
                top: 50%;
                width: 16px;
                height: 16px;
                transform: translateY(-50%);
                fill: none;
                stroke: var(--cv-muted);
                stroke-width: 2;
                stroke-linecap: round;
                pointer-events: none;
            }

            .cv-ui .cv-search input {
                width: 100%;
                height: 40px;
                padding: 0 12px 0 38px;
                border: 1px solid var(--cv-line);
                border-radius: 10px;
                background: var(--cv-surface);
                font-size: .86rem;
                color: var(--cv-text);
                transition: border-color .15s, box-shadow .15s, background .15s;
            }

            .cv-ui .cv-search input:focus {
                outline: none;
                background: #fff;
                border-color: var(--cv-accent);
                box-shadow: 0 0 0 3px var(--cv-accent-ring);
            }

            .cv-ui .cv-segment {
                display: inline-flex;
                padding: 3px;
                border-radius: 10px;
                background: var(--cv-surface);
                border: 1px solid var(--cv-line);
            }

            .cv-ui .cv-segment button {
                border: 0;
                background: transparent;
                padding: 6px 12px;
                border-radius: 8px;
                font-size: .8rem;
                color: var(--cv-muted);
                font-weight: 500;
                white-space: nowrap;
                transition: background .15s, color .15s;
            }

            .cv-ui .cv-segment button span {
                display: inline-block;
                min-width: 20px;
                margin-left: 4px;
                padding: 0 6px;
                border-radius: 999px;
                background: var(--cv-line);
                font-size: .7rem;
                line-height: 18px;
                font-variant-numeric: tabular-nums;
            }

            .cv-ui .cv-segment button.is-active {
                background: #fff;
                color: var(--cv-text);
                box-shadow: 0 1px 3px rgba(15, 23, 42, .1);
            }

            .cv-ui .cv-segment button.is-active span {
                background: var(--cv-accent);
                color: #fff;
            }

            .cv-ui .cv-bulk {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 8px;
                margin: 14px 2px 8px;
                font-size: .78rem;
            }

            .cv-ui .cv-muted {
                color: var(--cv-muted);
            }

            .cv-ui .cv-dot {
                color: var(--cv-line);
                margin: 0 4px;
            }

            .cv-ui .cv-link {
                border: 0;
                background: none;
                padding: 0;
                color: var(--cv-accent);
                font-weight: 500;
                font-size: .78rem;
            }

            .cv-ui .cv-link:hover {
                text-decoration: underline;
            }

            .cv-ui .cv-link-danger {
                color: #dc2626;
            }

            /* Lista */
            .cv-ui .cv-list {
                height: min(48vh, 420px);
                overflow-y: auto;
                border: 1px solid var(--cv-line);
                border-radius: 12px;
                overscroll-behavior: contain;
            }

            .cv-ui .cv-item {
                display: flex;
                align-items: center;
                gap: 12px;
                width: 100%;
                padding: 10px 14px;
                border: 0;
                border-bottom: 1px solid var(--cv-line);
                background: #fff;
                text-align: left;
                transition: background .12s;
            }

            .cv-ui .cv-item:last-child {
                border-bottom: 0;
            }

            .cv-ui .cv-item:hover {
                background: var(--cv-surface);
            }

            .cv-ui .cv-item:focus-visible {
                outline: 2px solid var(--cv-accent);
                outline-offset: -2px;
            }

            .cv-ui .cv-item.is-selected {
                background: var(--cv-accent-soft);
            }

            .cv-ui .cv-avatar {
                flex: 0 0 36px;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                display: grid;
                place-items: center;
                font-size: .75rem;
                font-weight: 600;
                color: #fff;
                letter-spacing: .02em;
            }

            .cv-ui .cv-info {
                flex: 1;
                min-width: 0;
            }

            .cv-ui .cv-name {
                display: block;
                font-size: .86rem;
                font-weight: 600;
                color: var(--cv-text);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .cv-ui .cv-email {
                display: block;
                font-size: .75rem;
                color: var(--cv-muted);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .cv-ui .cv-tag {
                flex: 0 0 auto;
                font-size: .68rem;
                font-weight: 600;
                padding: 2px 8px;
                border-radius: 999px;
            }

            .cv-ui .cv-tag-nuevo {
                background: #dcfce7;
                color: #166534;
            }

            .cv-ui .cv-tag-quitar {
                background: #fee2e2;
                color: #991b1b;
            }

            .cv-ui .cv-check {
                flex: 0 0 22px;
                width: 22px;
                height: 22px;
                border-radius: 7px;
                border: 1.5px solid #cfd5dc;
                display: grid;
                place-items: center;
                background: #fff;
                transition: background .12s, border-color .12s;
            }

            .cv-ui .cv-check svg {
                width: 14px;
                height: 14px;
                fill: none;
                stroke: #fff;
                stroke-width: 3;
                stroke-linecap: round;
                stroke-linejoin: round;
                opacity: 0;
                transform: scale(.6);
                transition: opacity .12s, transform .12s;
            }

            .cv-ui .cv-item.is-selected .cv-check {
                background: var(--cv-accent);
                border-color: var(--cv-accent);
            }

            .cv-ui .cv-item.is-selected .cv-check svg {
                opacity: 1;
                transform: scale(1);
            }

            /* Estados */
            .cv-ui .cv-state {
                height: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 6px;
                padding: 24px;
                text-align: center;
                color: var(--cv-muted);
                font-size: .85rem;
            }

            .cv-ui .cv-state strong {
                color: var(--cv-text);
                font-size: .92rem;
            }

            .cv-ui .cv-skel {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 14px;
                border-bottom: 1px solid var(--cv-line);
            }

            .cv-ui .cv-skel i {
                display: block;
                border-radius: 6px;
                background: linear-gradient(90deg, #f1f3f5 25%, #e7eaee 50%, #f1f3f5 75%);
                background-size: 200% 100%;
                animation: cvShimmer 1.2s infinite;
            }

            @keyframes cvShimmer {
                to {
                    background-position: -200% 0;
                }
            }

            .cv-ui .cv-more {
                padding: 10px 14px;
                font-size: .75rem;
                color: var(--cv-muted);
                text-align: center;
                background: var(--cv-surface);
            }

            /* Pie */
            .cv-ui .cv-foot {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                flex-wrap: wrap;
                padding: 16px 24px 20px;
            }

            .cv-ui .cv-resumen {
                font-size: .82rem;
                color: var(--cv-muted);
                display: flex;
                gap: 10px;
                align-items: center;
                flex-wrap: wrap;
            }

            .cv-ui .cv-resumen strong {
                color: var(--cv-text);
            }

            .cv-ui .cv-foot-actions {
                display: flex;
                gap: 8px;
            }

            .cv-ui .cv-foot-actions .btn {
                border-radius: 10px;
                padding: 8px 16px;
            }

            @media (max-width: 576px) {
                .cv-ui .cv-head,
                .cv-ui .cv-foot {
                    padding-left: 16px;
                    padding-right: 16px;
                }

                .cv-ui .cv-body {
                    padding: 0 16px;
                }

                .cv-ui .cv-segment {
                    width: 100%;
                }

                .cv-ui .cv-segment button {
                    flex: 1;
                }

                .cv-ui .cv-foot-actions {
                    width: 100%;
                }

                .cv-ui .cv-foot-actions .btn {
                    flex: 1;
                }
            }

            @media (prefers-reduced-motion: reduce) {
                .cv-ui * {
                    transition: none !important;
                    animation: none !important;
                }
            }

            /* ---------- Formulario crear / editar convenio ---------- */
            .cv-ui .cv-grid {
                display: grid;
                grid-template-columns: minmax(0, 5fr) minmax(0, 7fr);
                gap: 24px;
            }

            .cv-ui .cv-field {
                margin-bottom: 16px;
            }

            .cv-ui .cv-label {
                display: flex;
                justify-content: space-between;
                align-items: baseline;
                margin-bottom: 6px;
                font-size: .8rem;
                font-weight: 600;
                color: var(--cv-text);
            }

            .cv-ui .cv-label small {
                font-weight: 400;
                color: var(--cv-muted);
            }

            .cv-ui .cv-input {
                width: 100%;
                height: 42px;
                padding: 0 12px;
                border: 1px solid var(--cv-line);
                border-radius: 10px;
                background: var(--cv-surface);
                font-size: .88rem;
                color: var(--cv-text);
                transition: border-color .15s, box-shadow .15s, background .15s;
            }

            .cv-ui .cv-input:focus {
                outline: none;
                background: #fff;
                border-color: var(--cv-accent);
                box-shadow: 0 0 0 3px var(--cv-accent-ring);
            }

            .cv-ui .cv-input.is-invalid {
                border-color: #dc2626;
            }

            .cv-ui .cv-error {
                display: block;
                margin-top: 4px;
                font-size: .75rem;
                color: #dc2626;
            }

            .cv-ui .cv-affix {
                position: relative;
            }

            .cv-ui .cv-affix .cv-input {
                padding-right: 52px;
                font-variant-numeric: tabular-nums;
            }

            .cv-ui .cv-affix span {
                position: absolute;
                right: 6px;
                top: 50%;
                transform: translateY(-50%);
                min-width: 34px;
                padding: 3px 8px;
                border-radius: 7px;
                background: var(--cv-line);
                color: var(--cv-muted);
                font-size: .75rem;
                font-weight: 600;
                text-align: center;
            }

            .cv-ui .cv-types {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }

            .cv-ui .cv-type {
                position: relative;
                display: flex;
                align-items: center;
                gap: 10px;
                margin: 0;
                padding: 10px 12px;
                border: 1px solid var(--cv-line);
                border-radius: 12px;
                background: #fff;
                cursor: pointer;
                transition: border-color .15s, background .15s, box-shadow .15s;
            }

            .cv-ui .cv-type input {
                position: absolute;
                opacity: 0;
                pointer-events: none;
            }

            .cv-ui .cv-type:hover {
                background: var(--cv-surface);
            }

            .cv-ui .cv-type:has(input:focus-visible) {
                box-shadow: 0 0 0 3px var(--cv-accent-ring);
            }

            .cv-ui .cv-type.is-active,
            .cv-ui .cv-type:has(input:checked) {
                border-color: var(--cv-accent);
                background: var(--cv-accent-soft);
            }

            .cv-ui .cv-type-icon {
                flex: 0 0 32px;
                width: 32px;
                height: 32px;
                border-radius: 9px;
                display: grid;
                place-items: center;
                background: var(--cv-line);
                color: var(--cv-muted);
                font-weight: 700;
                font-size: .8rem;
            }

            .cv-ui .cv-type.is-active .cv-type-icon,
            .cv-ui .cv-type:has(input:checked) .cv-type-icon {
                background: var(--cv-accent);
                color: #fff;
            }

            .cv-ui .cv-type strong {
                display: block;
                font-size: .82rem;
                color: var(--cv-text);
            }

            .cv-ui .cv-type small {
                display: block;
                font-size: .7rem;
                color: var(--cv-muted);
                line-height: 1.25;
            }

            .cv-ui .cv-chips {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                min-height: 30px;
                max-height: 76px;
                overflow-y: auto;
                margin-bottom: 10px;
            }

            .cv-ui .cv-chip-sel {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                max-width: 220px;
                padding: 3px 4px 3px 10px;
                border-radius: 999px;
                background: var(--cv-accent-soft);
                color: var(--cv-accent);
                font-size: .74rem;
                font-weight: 600;
            }

            .cv-ui .cv-chip-sel span {
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }

            .cv-ui .cv-chip-sel button {
                flex: 0 0 20px;
                width: 20px;
                height: 20px;
                border: 0;
                border-radius: 50%;
                background: transparent;
                color: inherit;
                line-height: 1;
                font-size: .95rem;
            }

            .cv-ui .cv-chip-sel button:hover {
                background: var(--cv-accent-ring);
            }

            .cv-ui .cv-avatar-sq {
                border-radius: 10px;
            }

            .cv-ui .cv-price {
                flex: 0 0 auto;
                font-size: .76rem;
                font-weight: 600;
                color: var(--cv-text);
                font-variant-numeric: tabular-nums;
            }

            .cv-ui .cv-list-sm {
                height: min(40vh, 340px);
            }

            .cv-ui .cv-placeholder {
                font-size: .78rem;
                color: var(--cv-muted);
                align-self: center;
            }

            @media (max-width: 991.98px) {
                .cv-ui .cv-grid {
                    grid-template-columns: 1fr;
                    gap: 4px;
                }
            }

            /* ---------- Listado de convenios ---------- */
            .cv-ui .cl-card {
                border: 0;
                border-radius: 18px;
                overflow: hidden;
            }

            .cv-ui .cl-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                flex-wrap: wrap;
                padding: 22px 24px;
                border-bottom: 1px solid var(--cv-line);
            }

            .cv-ui .cl-title {
                margin: 0;
                font-size: 1.15rem;
                font-weight: 600;
                color: var(--cv-text);
            }

            .cv-ui .cl-sub {
                margin: 2px 0 0;
                font-size: .8rem;
                color: var(--cv-muted);
            }

            .cv-ui .cl-header-actions {
                display: flex;
                align-items: center;
                gap: 10px;
                flex: 1 1 420px;
                justify-content: flex-end;
            }

            .cv-ui .cl-search {
                flex: 0 1 420px;
            }

            .cv-ui .cl-btn-nuevo {
                height: 40px;
                border-radius: 10px;
                padding: 0 16px;
                white-space: nowrap;
                font-size: .85rem;
            }

            .cv-ui .cl-table {
                padding: 4px 12px 0;
            }

            .cv-ui .cl-row {
                display: grid;
                grid-template-columns: minmax(150px, 1.1fr) 130px minmax(240px, 2.6fr) 170px 88px;
                gap: 16px;
                align-items: center;
                padding: 14px 12px;
                border-bottom: 1px solid var(--cv-line);
            }

            .cv-ui .cl-row:not(.cl-row-head):hover {
                background: var(--cv-surface);
                border-radius: 12px;
            }

            .cv-ui .cl-row-head {
                padding-top: 12px;
                padding-bottom: 10px;
                font-size: .72rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .05em;
                color: var(--cv-muted);
            }

            .cv-ui .cl-right {
                text-align: right;
            }

            .cv-ui .cl-cell {
                min-width: 0;
            }

            .cv-ui .cl-name {
                display: block;
                font-size: .88rem;
                font-weight: 600;
                color: var(--cv-text);
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .cv-ui .cl-meta {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                margin-top: 2px;
                font-size: .74rem;
                color: var(--cv-muted);
            }

            .cv-ui .cl-meta i {
                font-size: .7rem;
            }

            .cv-ui .cl-discount {
                display: inline-flex;
                flex-direction: column;
                padding: 6px 12px;
                border-radius: 10px;
                line-height: 1.2;
            }

            .cv-ui .cl-discount strong {
                font-size: .95rem;
                font-variant-numeric: tabular-nums;
            }

            .cv-ui .cl-discount small {
                font-size: .68rem;
                opacity: .85;
            }

            .cv-ui .cl-discount.is-fijo {
                background: var(--cv-accent-soft);
                color: var(--cv-accent);
            }

            .cv-ui .cl-discount.is-pct {
                background: #eef2ff;
                color: #4338ca;
            }

            .cv-ui .cl-products-count {
                display: block;
                margin-bottom: 5px;
                font-size: .72rem;
                color: var(--cv-muted);
            }

            .cv-ui .cl-chips {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
                align-items: flex-start;
            }

            .cv-ui .cl-chip {
                display: inline-block;
                max-width: 210px;
                padding: 3px 10px;
                border-radius: 999px;
                border: 1px solid var(--cv-line);
                background: #fff;
                color: var(--cv-text);
                font-size: .74rem;
                font-weight: 500;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .cv-ui .cl-chip.is-missing {
                border-style: dashed;
                color: #b91c1c;
                background: #fef2f2;
            }

            .cv-ui .cl-more {
                display: inline-block;
            }

            .cv-ui .cl-more[open] {
                flex-basis: 100%;
            }

            .cv-ui .cl-more summary {
                list-style: none;
                display: inline-block;
                padding: 3px 10px;
                border-radius: 999px;
                background: var(--cv-accent-soft);
                color: var(--cv-accent);
                font-size: .74rem;
                font-weight: 600;
                cursor: pointer;
                user-select: none;
            }

            .cv-ui .cl-more summary::-webkit-details-marker {
                display: none;
            }

            .cv-ui .cl-more summary:focus-visible {
                outline: 2px solid var(--cv-accent);
                outline-offset: 2px;
            }

            .cv-ui .cl-more .cl-more-close,
            .cv-ui .cl-more[open] .cl-more-open {
                display: none;
            }

            .cv-ui .cl-more[open] .cl-more-close {
                display: inline;
            }

            .cv-ui .cl-chips-extra {
                margin-top: 5px;
            }

            .cv-ui .cl-status {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 3px 10px;
                border-radius: 999px;
                font-size: .74rem;
                font-weight: 600;
            }

            .cv-ui .cl-status::before {
                content: "";
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: currentColor;
            }

            .cv-ui .cl-status.is-vigente {
                background: #dcfce7;
                color: #166534;
            }

            .cv-ui .cl-status.is-pronto {
                background: #fef3c7;
                color: #92400e;
            }

            .cv-ui .cl-status.is-vencido {
                background: #fee2e2;
                color: #991b1b;
            }

            .cv-ui .cl-status.is-sinfecha {
                background: var(--cv-line);
                color: var(--cv-muted);
            }

            .cv-ui .cl-date {
                display: block;
                margin-top: 4px;
                font-size: .74rem;
                color: var(--cv-muted);
            }

            .cv-ui .cl-actions {
                display: flex;
                justify-content: flex-end;
                gap: 6px;
            }

            .cv-ui .cl-icon-btn {
                width: 34px;
                height: 34px;
                border-radius: 10px;
                border: 1px solid var(--cv-line);
                background: #fff;
                color: var(--cv-muted);
                display: grid;
                place-items: center;
                font-size: .8rem;
                transition: background .15s, color .15s, border-color .15s;
            }

            .cv-ui .cl-icon-btn:hover {
                background: var(--cv-accent-soft);
                border-color: var(--cv-accent);
                color: var(--cv-accent);
            }

            .cv-ui .cl-icon-btn.is-danger:hover {
                background: #fef2f2;
                border-color: #dc2626;
                color: #dc2626;
            }

            .cv-ui .cl-icon-btn:focus-visible {
                outline: 2px solid var(--cv-accent);
                outline-offset: 2px;
            }

            .cv-ui .cl-muted {
                font-size: .8rem;
                color: var(--cv-muted);
            }

            .cv-ui .cl-empty {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 4px;
                padding: 48px 16px;
                text-align: center;
                font-size: .85rem;
                color: var(--cv-muted);
            }

            .cv-ui .cl-empty strong {
                color: var(--cv-text);
                font-size: .95rem;
            }

            .cv-ui .cl-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                flex-wrap: wrap;
                padding: 14px 24px 18px;
            }

            .cv-ui .cl-footer .pagination {
                margin: 0;
            }

            /* Tablet: la columna de vigencia baja bajo el nombre */
            @media (max-width: 1199.98px) {
                .cv-ui .cl-row {
                    grid-template-columns: minmax(150px, 1fr) 120px minmax(200px, 2fr) 88px;
                }

                .cv-ui .cl-row > :nth-child(4) {
                    grid-column: 1;
                    grid-row: 2;
                }

                .cv-ui .cl-row-head > :nth-child(4) {
                    display: none;
                }
            }

            /* Móvil: cada convenio es una tarjeta */
            @media (max-width: 767.98px) {
                .cv-ui .cl-header {
                    padding: 18px 16px;
                }

                .cv-ui .cl-header-actions {
                    flex-basis: 100%;
                }

                .cv-ui .cl-search {
                    flex: 1 1 auto;
                }

                .cv-ui .cl-table {
                    padding: 4px 8px 0;
                }

                .cv-ui .cl-row-head {
                    display: none;
                }

                .cv-ui .cl-row {
                    grid-template-columns: 1fr auto;
                    gap: 10px 12px;
                    padding: 14px 8px;
                }

                .cv-ui .cl-row > .cl-cell-main {
                    grid-column: 1;
                    grid-row: 1;
                }

                .cv-ui .cl-row > .cl-actions {
                    grid-column: 2;
                    grid-row: 1;
                }

                .cv-ui .cl-row > .cl-cell[data-label] {
                    grid-column: 1 / -1;
                    grid-row: auto;
                }

                .cv-ui .cl-cell[data-label]::before {
                    content: attr(data-label);
                    display: block;
                    margin-bottom: 4px;
                    font-size: .68rem;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: .05em;
                    color: var(--cv-muted);
                }

                .cv-ui .cl-chip {
                    max-width: 100%;
                }

                .cv-ui .cl-footer {
                    padding: 14px 16px 18px;
                }
            }

            /* ---------- Listado de vínculos (clientes por convenio) ---------- */
            .cv-ui .cl-row.cl-row-vinc {
                grid-template-columns: minmax(150px, 1.2fr) 130px minmax(220px, 2fr) 170px 150px;
            }

            .cv-ui .cl-clients {
                display: inline-flex;
                align-items: center;
                gap: 12px;
                max-width: 100%;
                padding: 4px 10px 4px 4px;
                border: 1px solid transparent;
                border-radius: 999px;
                background: transparent;
                text-align: left;
                transition: background .15s, border-color .15s;
            }

            .cv-ui .cl-clients:hover {
                background: #fff;
                border-color: var(--cv-line);
            }

            .cv-ui .cl-clients:focus-visible {
                outline: 2px solid var(--cv-accent);
                outline-offset: 2px;
            }

            .cv-ui .cl-avatars {
                display: inline-flex;
                padding-left: 8px;
            }

            .cv-ui .cl-av {
                width: 30px;
                height: 30px;
                margin-left: -8px;
                border-radius: 50%;
                border: 2px solid #fff;
                display: grid;
                place-items: center;
                color: #fff;
                font-size: .64rem;
                font-weight: 600;
                letter-spacing: .02em;
            }

            .cv-ui .cl-av-more {
                background: var(--cv-line);
                color: var(--cv-text);
            }

            .cv-ui .cl-clients-text {
                display: flex;
                flex-direction: column;
                line-height: 1.2;
                min-width: 0;
            }

            .cv-ui .cl-clients-text strong {
                font-size: .82rem;
                color: var(--cv-text);
            }

            .cv-ui .cl-clients-text small {
                font-size: .72rem;
                color: var(--cv-accent);
                font-weight: 500;
            }

            .cv-ui .cl-btn-vincular {
                height: 34px;
                padding: 0 12px;
                border-radius: 10px;
                font-size: .78rem;
                white-space: nowrap;
            }

            @media (max-width: 1199.98px) {
                .cv-ui .cl-row.cl-row-vinc {
                    grid-template-columns: minmax(150px, 1fr) 120px minmax(200px, 2fr) 150px;
                }
            }

            @media (max-width: 767.98px) {
                .cv-ui .cl-row.cl-row-vinc {
                    grid-template-columns: 1fr auto;
                }
            }

            /* ---------- Perfiles de puntos ---------- */
            .cv-ui .pp-card {
                overflow: visible;
            }

            .cv-ui .cl-row.pp-row {
                grid-template-columns: minmax(150px, 1.1fr) 120px 130px minmax(200px, 1.7fr) 150px 88px;
            }

            .cv-ui .pp-th {
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            /* Icono "i" + globo explicativo (solo CSS, sobrevive a los re-render de Livewire) */
            .cv-ui .pp-tip {
                position: relative;
                display: inline-flex;
                align-items: center;
            }

            .cv-ui .pp-info {
                width: 18px;
                height: 18px;
                padding: 0;
                border: 0;
                border-radius: 50%;
                background: var(--cv-accent-soft);
                color: var(--cv-accent);
                font: italic 700 .7rem/18px Georgia, 'Times New Roman', serif;
                text-transform: none;
                cursor: help;
                transition: background .15s, color .15s;
            }

            .cv-ui .pp-tip:hover .pp-info,
            .cv-ui .pp-info:focus-visible {
                background: var(--cv-accent);
                color: #fff;
                outline: none;
            }

            .cv-ui .pp-pop {
                position: absolute;
                top: calc(100% + 8px);
                left: 50%;
                z-index: 30;
                width: 260px;
                padding: 12px 14px;
                border-radius: 12px;
                background: #1f2937;
                color: #e5e7eb;
                box-shadow: 0 12px 30px rgba(15, 23, 42, .25);
                font-size: .76rem;
                font-weight: 400;
                line-height: 1.45;
                letter-spacing: normal;
                text-transform: none;
                text-align: left;
                white-space: normal;
                opacity: 0;
                visibility: hidden;
                transform: translate(-50%, 4px);
                transition: opacity .15s, transform .15s, visibility .15s;
                pointer-events: none;
            }

            .cv-ui .pp-pop strong {
                display: block;
                margin-bottom: 3px;
                color: #fff;
                font-size: .8rem;
            }

            .cv-ui .pp-pop::before {
                content: "";
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                border: 6px solid transparent;
                border-bottom-color: #1f2937;
            }

            .cv-ui .pp-pop-end {
                left: auto;
                right: -12px;
                transform: translate(0, 4px);
            }

            .cv-ui .pp-pop-end::before {
                left: auto;
                right: 15px;
                transform: none;
            }

            .cv-ui .pp-tip:hover .pp-pop,
            .cv-ui .pp-tip:focus-within .pp-pop {
                opacity: 1;
                visibility: visible;
                transform: translate(-50%, 0);
            }

            .cv-ui .pp-tip:hover .pp-pop-end,
            .cv-ui .pp-tip:focus-within .pp-pop-end {
                transform: translate(0, 0);
            }

            .cv-ui .pp-badge {
                margin-top: 4px;
                font-size: .68rem;
                font-weight: 600;
            }

            .cv-ui .pp-badge > span[tabindex] {
                padding: 2px 8px;
                border-radius: 999px;
                background: #e0f2fe;
                color: #075985;
                cursor: help;
            }

            .cv-ui .pp-badge > span[tabindex]:focus-visible {
                outline: 2px solid var(--cv-accent);
                outline-offset: 2px;
            }

            .cv-ui .pp-badge .pp-pop {
                left: 0;
                transform: translate(0, 4px);
                width: 220px;
            }

            .cv-ui .pp-badge .pp-pop::before {
                left: 20px;
            }

            .cv-ui .pp-badge:hover .pp-pop,
            .cv-ui .pp-badge:focus-within .pp-pop {
                transform: translate(0, 0);
            }

            .cv-ui .pp-metric {
                display: inline-flex;
                flex-direction: column;
                line-height: 1.2;
            }

            .cv-ui .pp-metric strong {
                font-size: 1rem;
                color: #b45309;
                font-variant-numeric: tabular-nums;
            }

            .cv-ui .pp-metric-bono strong {
                color: var(--cv-accent);
            }

            .cv-ui .pp-metric small {
                font-size: .7rem;
                color: var(--cv-muted);
            }

            .cv-ui .pp-split {
                max-width: 260px;
            }

            .cv-ui .pp-bar {
                display: flex;
                height: 8px;
                border-radius: 999px;
                overflow: hidden;
                background: var(--cv-line);
            }

            .cv-ui .pp-bar-ref {
                background: #f59e0b;
            }

            .cv-ui .pp-bar-cli {
                background: var(--cv-accent);
            }

            .cv-ui .pp-legend {
                display: flex;
                justify-content: space-between;
                gap: 8px;
                margin-top: 6px;
                font-size: .72rem;
                color: var(--cv-muted);
            }

            .cv-ui .pp-legend b {
                color: var(--cv-text);
                font-variant-numeric: tabular-nums;
            }

            .cv-ui .pp-dot {
                display: inline-block;
                width: 8px;
                height: 8px;
                margin-right: 5px;
                border-radius: 50%;
            }

            .cv-ui .pp-dot-ref {
                background: #f59e0b;
            }

            .cv-ui .pp-dot-cli {
                background: var(--cv-accent);
            }

            .cv-ui .pp-users {
                display: inline-flex;
                align-items: baseline;
                gap: 6px;
                padding: 6px 12px;
                border: 1px solid var(--cv-line);
                border-radius: 10px;
                background: #fff;
                color: var(--cv-text);
                font-size: .78rem;
                transition: border-color .15s, background .15s;
            }

            .cv-ui .pp-users strong {
                font-size: .92rem;
                font-variant-numeric: tabular-nums;
            }

            .cv-ui .pp-users span {
                color: var(--cv-muted);
            }

            .cv-ui .pp-users:hover {
                border-color: var(--cv-accent);
                background: var(--cv-accent-soft);
            }

            .cv-ui .pp-users.is-empty strong {
                display: none;
            }

            .cv-ui .pp-users.is-empty span {
                color: var(--cv-accent);
                font-weight: 600;
            }

            /* Guía desplegable */
            .cv-ui .pp-guide {
                margin: 14px 24px 0;
                border: 1px solid var(--cv-line);
                border-radius: 12px;
                background: var(--cv-surface);
            }

            .cv-ui .pp-guide summary {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 12px 16px;
                list-style: none;
                cursor: pointer;
                font-size: .84rem;
                font-weight: 600;
                color: var(--cv-text);
            }

            .cv-ui .pp-guide summary::-webkit-details-marker {
                display: none;
            }

            .cv-ui .pp-guide summary .fa {
                color: var(--cv-accent);
            }

            .cv-ui .pp-guide-chevron {
                margin-left: auto;
                width: 8px;
                height: 8px;
                border-right: 2px solid var(--cv-muted);
                border-bottom: 2px solid var(--cv-muted);
                transform: rotate(45deg);
                transition: transform .15s;
            }

            .cv-ui .pp-guide[open] .pp-guide-chevron {
                transform: rotate(-135deg);
            }

            .cv-ui .pp-guide-body {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 16px;
                padding: 4px 16px 16px;
            }

            .cv-ui .pp-step {
                display: flex;
                gap: 10px;
            }

            .cv-ui .pp-step-n {
                flex: 0 0 24px;
                width: 24px;
                height: 24px;
                border-radius: 50%;
                display: grid;
                place-items: center;
                background: var(--cv-accent);
                color: #fff;
                font-size: .72rem;
                font-weight: 700;
            }

            .cv-ui .pp-step strong {
                display: block;
                font-size: .8rem;
                color: var(--cv-text);
            }

            .cv-ui .pp-step p {
                margin: 3px 0 0;
                font-size: .76rem;
                line-height: 1.45;
                color: var(--cv-muted);
            }

            .cv-ui .pp-guide-note {
                grid-column: 1 / -1;
                margin: 0;
                padding-top: 12px;
                border-top: 1px dashed var(--cv-line);
                font-size: .74rem;
                color: var(--cv-muted);
            }

            @media (max-width: 1199.98px) {
                .cv-ui .cl-row.pp-row {
                    grid-template-columns: minmax(150px, 1fr) 110px 120px minmax(180px, 1.5fr) 88px;
                }

                .cv-ui .cl-row.pp-row > :nth-child(4) {
                    grid-column: auto;
                    grid-row: auto;
                }

                .cv-ui .cl-row-head.pp-row > :nth-child(4) {
                    display: block;
                }

                .cv-ui .cl-row.pp-row > :nth-child(5) {
                    grid-column: 1;
                    grid-row: 2;
                }

                .cv-ui .cl-row-head.pp-row > :nth-child(5) {
                    display: none;
                }

                .cv-ui .pp-guide-body {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 767.98px) {
                .cv-ui .cl-row.pp-row {
                    grid-template-columns: 1fr auto;
                }

                .cv-ui .cl-row.pp-row > .cl-cell[data-label] {
                    grid-column: 1 / -1;
                    grid-row: auto;
                }

                .cv-ui .pp-guide {
                    margin: 12px 16px 0;
                }

                .cv-ui .pp-pop,
                .cv-ui .pp-pop-end {
                    width: 220px;
                }
            }
        </style>
        <script>
            // Ajusta el color de acento al primario real del tema activo
            window.cvAplicarColorTema = function(elemento) {
                if (!elemento) return;
                const probe = document.createElement('button');
                probe.className = 'btn btn-primary';
                probe.style.cssText = 'position:absolute;visibility:hidden;pointer-events:none';
                document.body.appendChild(probe);
                const m = (getComputedStyle(probe).backgroundColor || '').match(/\d+/g);
                probe.remove();
                if (!m || m.length < 3) return;
                const [r, g, b] = m;
                elemento.style.setProperty('--cv-accent', `rgb(${r}, ${g}, ${b})`);
                elemento.style.setProperty('--cv-accent-soft', `rgba(${r}, ${g}, ${b}, .08)`);
                elemento.style.setProperty('--cv-accent-ring', `rgba(${r}, ${g}, ${b}, .22)`);
            };
        </script>
