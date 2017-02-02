<?php

namespace Citizens;

class Config {

	private $plugin;

    public function __construct($plugin) {
        $this->plugin = $plugin;
    }
	
	public function load() {
		if (!file_exists("./plugins/Citizens") && !is_dir("./plugins/Citizens")) {
		    mkdir("./plugins/Citizens");
		}
		if (!file_exists("./plugins/Citizens/skins") && !is_dir("./plugins/Citizens/skins")) {
		    mkdir("./plugins/Citizens/skins");
		}
		if (!file_exists("./plugins/Citizens/npcs") && !is_dir("./plugins/Citizens/npcs")) {
		    mkdir("./plugins/Citizens/npcs");
		}
		if (!file_exists("./plugins/Citizens/npcs/_all.json") && !is_dir("./plugins/Citizens/npcs/_all.json")) {
		    $json = fopen("./plugins/Citizens/npcs/_all.json", "wb");
			fwrite($json, json_encode(array()));
			fclose($json);
		}
		if (!file_exists("./plugins/Citizens/skins/default.png") && !is_dir("./plugins/Citizens/skins/default.png")) {

			//Base64 encoded Steve skin
			$base64 = "iVBORw0KGgoAAAANSUhEUgAAAEAAAAAgCAYAAACinX6EAAAGHUlEQVR4nNRYXWwUVRt+Z3Z29oftdj/6+xU+4KMEBEyUkCgYCBdE5cYLQbhQ40+iMRo13hq98MLEGw2JJhDv0JiQGBITFbzQcKNJhYB4QShiK4Wg/aGl2+52f2Z3ZjzPmT27Z6azw3T5afsk2zkz5z2n87w/z3l3Ndu2KQhb1mS4gVGpkB6Nkhjza1mlAzs3Ba4/fPKcEmiwyFDDGIFwMhYjTXWb6zGLX+fKRJmUXr/is1yghTECeaDEHKFFIr42H3w94Lp/de92WhG7w7e7D7htBoh0r1oWWZYTcZQCnCJKYjnjtg6QSaqsBKqmOa8UljNCMUH0QRyIh4z62Phc6291HxFKA0AexCvsCh0oFZnYpd2OQM0vR4RyQISlfKmmBUA84WSFwHIQu2ZQNq1K8XPeYn8VRSGVfeIxjUW9ZkAmmYxrRFHJMKtUZRPQgqgWIbQQkdqpwG6pVK6yfWxCb6HWTn9Vdeab9RHXb+UWtU9wZQB/cUYIRFTGIKZrVDacOdO2KBnXKR1TyWLSMZUrM7KsNDTYVKlUtbljzGp13j8RfQQgZ47oIxYT7FjXyDBMUlSbpzoAR5TZM8uyuSM+euslFj2dEvE0FedmURM0PT5KH584RYWiQRWWFVrEWYs9TEbSNBXS9QjfK0wfsVhQTdOJts4iySPI7lETMT3KCETpvZefJbOi0K3pPI1NTNHI6ARZFYuyuVl65cld3Aa2WOPspdb2Uvj9Uu8jeNgUBcdclUeyUrXo+T3b6M19j1GSRTDBXvTpdw/T8V/XkF41aE1PNx39aSW9/tk31N7xX24DW6zBWuyBvbAnsNT7iLoI2rbKUx4RfebRDbSyLUM5o0gdyTR1966iaKydvj99mi86sG833bg+RFfHJplgRqlNT9CtXJZOnBli5VTh0RcOECLo10cgKy5dzy6qCCr4todU1ZgWbN+4nnZu/j+Z5QJNZrOUzRvU19NJOiNz7UbOtbC3L0m5uQrl5wr8y09nJkORWJIGBq/S+St/8SyAvkAD5D4C+iD6iKXgAO3Qjs18AAIgbZtlftRpTPRsMmjkn3Feu52Zdm5XrhisLOI0VzT5GOkD22hUYzVfpkf6V9OOjX1ksT2wHwBHnvz9j/o/RR+xd+v6JfGtUfH+HvDUc2ddD4Z+e9E1Pzg4GBixQwNn7VUfvuE79/f7R+jC0SOBL/Tnl8cC93/iq+N2pr+fj7PDw7Tu2Cc0ls1TbybFr9+eu7ygjArVCS4U+F3gXkGQ945bxT1xwP0CMqAkRR/XheKenEf4buD3uRsA6bsJZeu2d3jNF4sTlEh0U0eP+1vd1Pj5+hwgzxfyN8jaU3C93Ordu0llir/rxy9oXW87jYzN8LlfHne0ZPbaNdf+6bVrKZpI1u8jta7RLDt1NHV50NcedrDxznc/9LDr/oeD+wM1QRPkW0EytZrydIUKE8561GQs7ZwWnDBzghjHM//hYzhA2AMdD2yuk/aDbJvs7ub7COdUigWXDeYXiroGiAgvFPILAjIZELdYKxyTIhxk730OoiAlr8EzsQbO8GbUQnHHGmDkGg2SXJ8yMXks28vjwk2HJAiKsR9E1IFSdrrFt24g1CmA7BA6gLqXof+vzZdIsstJV3Evk5LtAZHSXsBxL4yeqd/PDF+mDdoW1lA0bIaGL1F7subg0avUpU26Nzm4P5CbbwaApEw0SCNkMnpbm2tOJia0QbYR4/LszLx9mzkFmJxt/N4I8jOF1hsP3wyAuHkhNMI7l28bmRdRRB8AMUHcC9kRsIGtXN+4xz4gV4+wB7Ij/J53plf4zsvgDmhVAAFZecOosJ9N0CkgQziiGTE4qytg3g+hNSAsRPS98CPpcl6X/1ggKBOaPQ8DrTN9gQ8mZ7cFGgq7B/srrucDtJJfBRlZ7JDa4jhrFmXZHsRlEcW6IHKI9E1JPoQtSgBzzUpEhnY7gheLFwM3KJ/KkVHMUSSR4GKZWrexMUf4EaTABbXz7cZz42dHM7Am1ZOqC27stYZeTH56xdGbPv//K0iKzAjKkCDc1e8CzUoFREAIgMO8tpjHZ/rzWlPzXWqe2MrRFfCeBq3g3wAAAP//rBTfQARaID0AAAAASUVORK5CYII=";
		    $image = fopen("./plugins/Citizens/skins/default.png", "wb");
			fwrite($image, base64_decode($base64));
			fclose($image);
		}
	}
}