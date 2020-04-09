export default interface Transaction {
  id: number;
  from: number;
  to: number;
  details: string;
  amount: number|string;
  created_at: string;
  updated_at: string;
}
