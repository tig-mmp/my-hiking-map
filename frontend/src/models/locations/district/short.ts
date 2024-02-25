import { CountyShort } from "@/models/locations/county/short";

export interface DistrictShort {
  id: number;
  name: string;
  counties: CountyShort[];
}

const districtShortDataType = "short";
export { districtShortDataType };
