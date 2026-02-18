# Design System Documentation

## Overview

This design system is created for the Pupuk New POS application redesign, focusing on accessibility (WCAG 2.2 AA), modern aesthetics, and efficient user flows.

## 1. Color Palette

### Primary Colors (Brand)

Based on `tailwind.config.js` and visual requirements:

- **Primary**: Emerald-600 (`#059669`) - Main actions, active states.
- **Primary Hover**: Emerald-700 (`#047857`) - Hover states.
- **Primary Light**: Emerald-50 (`#ecfdf5`) - Backgrounds for active items.

### Secondary/Neutral Colors

- **Gray-50**: Page background.
- **Gray-100/200**: Borders, dividers.
- **Gray-500/600**: Secondary text, icons.
- **Gray-800/900**: Primary text, headings.
- **White**: Card backgrounds.

### Functional Colors

- **Success**: Emerald-600 (same as primary).
- **Error**: Red-600 (`#dc2626`) - Form errors, delete actions.
- **Warning**: Amber-500 (`#f59e0b`) - Alerts, pending states.
- **Info**: Blue-500 (`#3b82f6`) - Information notices.

### Dark Mode

- **Background**: Gray-900 (`#111827`).
- **Surface**: Gray-800 (`#1f2937`).
- **Text**: Gray-100/300.

## 2. Typography

**Font Family**: `Plus Jakarta Sans`, sans-serif.

### Scale

- **Display**: 24px/32px (1.5rem/2rem) - Page Titles.
- **Heading**: 18px/28px (1.125rem/1.75rem) - Card Titles.
- **Body Large**: 16px/24px (1rem/1.5rem) - Major labels, inputs.
- **Body**: 14px/20px (0.875rem/1.25rem) - Default text.
- **Caption**: 12px/16px (0.75rem/1rem) - Helpers, metadata.

### Weights

- **Regular (400)**: Body text.
- **Medium (500)**: Buttons, labels, table headers.
- **Semibold (600)**: Headings, emphasized data.
- **Bold (700)**: Key metrics (Totals).

## 3. Spacing System (8-point Grid)

- **xs (4px)**: Tight gaps.
- **sm (8px)**: Standard gap between related elements.
- **md (16px)**: Section padding, component separation.
- **lg (24px)**: Card padding, major section separation.
- **xl (32px)**: Layout margins.

## 4. Components

### Buttons

- **Primary**: Emerald-600 bg, White text, rounded-xl. Hover: Emerald-700. Focus: Ring-2 Emerald-500 offset-2.
- **Secondary**: White bg, Gray-300 border, Gray-700 text. Hover: Gray-50.
- **Ghost**: Transparent bg, Gray-600 text. Hover: Gray-100.
- **Icon Button**: p-2 rounded-lg.

### Inputs

- **Base**: rounded-xl border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500.
- **Height**: 42px (touch-friendly).
- **Label**: Text-sm font-medium text-gray-700, mb-1.

### Cards

- **Base**: White bg, rounded-xl (12px), shadow-sm border border-gray-100.
- **Interactive**: Hover: shadow-md transition-shadow.

### Navigation

- **Top Bar**: Sticky, white bg/backdrop-blur, h-16.
- **Sidebar**: Collapsible, dark/light mode compatible.

## 5. Accessibility Guidelines (WCAG 2.2 AA)

- **Contrast**: Text min 4.5:1. Large text 3:1.
- **Focus**: Visible focus rings for keyboard navigation.
- **Labels**: All inputs must have associated labels.
- **ARIA**: Use `aria-label`, `aria-expanded`, `role` where semantic HTML is insufficient.
- **Touch**: Minimum target size 44x44px.

## 6. Responsive Breakpoints

- **Mobile**: < 640px (Stack layout, full width).
- **Tablet**: 640px - 1024px (Grid 2 cols, adaptive sidebar).
- **Desktop**: > 1024px (Full layout, sidebar persistent).
