import { LocationShort } from "@/models/locations/location/short";

export interface CountyShort {
  id: number;
  name: string;
  locations: LocationShort[];
}

const countyShortDataType = "short";
export { countyShortDataType };
