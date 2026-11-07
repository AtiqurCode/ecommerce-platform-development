export function formatMoney(value: string | number): string {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(Number(value));
}

export function discountPercent(
    oldPrice: string | number,
    newPrice: string | number,
): number | null {
    const oldValue = Number(oldPrice);
    const newValue = Number(newPrice);

    if (oldValue <= 0 || newValue >= oldValue) {
        return null;
    }

    return Math.round(((oldValue - newValue) / oldValue) * 100);
}
