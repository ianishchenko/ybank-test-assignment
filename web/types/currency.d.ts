interface Rates {
  EUR: number,
  USD: number
}

export default interface Currency {
  rates: Rates
  date: string,
  base: string
}


