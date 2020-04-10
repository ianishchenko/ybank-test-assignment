export default interface Transaction<T extends string | number> {
  id: number;
  from: number;
  to: number;
  details: string;
  amount: T;
  current_currency: number;
  created_at: string;
  updated_at: string;
}
