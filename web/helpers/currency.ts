export function usdToEuro(usd: number, currency: number): number {
  return +(usd * currency).toFixed(2);
}
